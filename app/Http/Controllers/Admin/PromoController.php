<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\DailyPromoCodeMail;
use App\Models\Promo;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class PromoController extends Controller
{
    public function index(Request $request)
    {
        $status = (string) $request->query('status', 'all');
        $search = trim((string) $request->query('search', ''));

        $query = Promo::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('code', 'like', '%' . $search . '%')
                    ->orWhere('name', 'like', '%' . $search . '%');
            });
        }

        if ($status === 'active') {
            $query->where('is_active', true)
                ->where(function ($q): void {
                    $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
                })
                ->where('expires_at', '>', now());
        } elseif ($status === 'expired') {
            $query->where('expires_at', '<=', now());
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $promos = $query->latest()->paginate(12)->withQueryString();

        $stats = [
            'total' => Promo::count(),
            'active' => Promo::where('is_active', true)
                ->where(function ($q): void {
                    $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
                })
                ->where('expires_at', '>', now())
                ->count(),
            'expired' => Promo::where('expires_at', '<=', now())->count(),
            'inactive' => Promo::where('is_active', false)->count(),
        ];

        return view('admin.promos.index', compact('promos', 'stats', 'status', 'search'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|alpha_dash|unique:promos,code',
            'name' => 'nullable|string|max:120',
            'discount_percent' => 'required|numeric|min:5|max:100',
            'starts_at' => 'nullable|date',
            'expires_at' => 'required|date|after:starts_at',
            'is_active' => 'nullable|boolean',
            'send_announcement' => 'nullable|boolean',
        ]);

        $promo = Promo::create([
            'code' => strtoupper($validated['code']),
            'name' => $validated['name'] ?? null,
            'discount_percent' => $validated['discount_percent'],
            'starts_at' => !empty($validated['starts_at']) ? Carbon::parse($validated['starts_at']) : now(),
            'expires_at' => Carbon::parse($validated['expires_at']),
            'is_active' => (bool) ($validated['is_active'] ?? true),
        ]);

        $queuedRecipients = null;
        if ((bool) ($validated['send_announcement'] ?? false)) {
            $queuedRecipients = 0;
            try {
                $queuedRecipients = $this->queuePromoAnnouncementEmails($promo);
            } catch (\Throwable $e) {
                Log::error('Failed to queue promo announcement emails', [
                    'promo_id' => $promo->id,
                    'promo_code' => $promo->code,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $toastMessage = 'Promo created successfully.';
        if ($queuedRecipients !== null) {
            $toastMessage .= " Announcement queued to {$queuedRecipients} users.";
        }

        return back()->with([
            'toast_type' => 'success',
            'toast_message' => $toastMessage,
        ]);
    }

    public function update(Request $request, Promo $promo): RedirectResponse
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                Rule::unique('promos', 'code')->ignore($promo->id),
            ],
            'name' => 'nullable|string|max:120',
            'discount_percent' => 'required|numeric|min:5|max:100',
            'starts_at' => 'nullable|date',
            'expires_at' => 'required|date|after:starts_at',
            'is_active' => 'nullable|boolean',
        ]);

        $promo->update([
            'code' => strtoupper($validated['code']),
            'name' => $validated['name'] ?? null,
            'discount_percent' => $validated['discount_percent'],
            'starts_at' => !empty($validated['starts_at']) ? Carbon::parse($validated['starts_at']) : $promo->starts_at,
            'expires_at' => Carbon::parse($validated['expires_at']),
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        return back()->with([
            'toast_type' => 'success',
            'toast_message' => 'Promo updated successfully.',
        ]);
    }

    public function toggle(Promo $promo): RedirectResponse
    {
        $promo->update([
            'is_active' => ! $promo->is_active,
        ]);

        return back()->with([
            'toast_type' => 'success',
            'toast_message' => $promo->is_active ? 'Promo activated.' : 'Promo deactivated.',
        ]);
    }

    public function destroy(Promo $promo): RedirectResponse
    {
        $promo->delete();

        return back()->with([
            'toast_type' => 'success',
            'toast_message' => 'Promo deleted successfully.',
        ]);
    }

    private function queuePromoAnnouncementEmails(Promo $promo): int
    {
        $query = User::query()
            ->whereNotNull('email_verified_at');

        if (Schema::hasColumn('users', 'archived_at')) {
            $query->whereNull('archived_at');
        }

        $sent = 0;

        $query->select(['id', 'first_name', 'email'])
            ->orderBy('id')
            ->chunkById(200, function ($users) use ($promo, &$sent): void {
                foreach ($users as $user) {
                    Mail::to($user->email)->queue(new DailyPromoCodeMail($promo, $user));
                    $sent++;
                }
            });

        return $sent;
    }
}
