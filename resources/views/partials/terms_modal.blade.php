<div id="termsModal" class="fixed inset-0 z-50 hidden flex items-center justify-center px-4 py-8">
    <div class="absolute inset-0 bg-black/60" onclick="closeTermsModal()"></div>
    <div class="relative w-full max-w-3xl bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200">
        <div class="flex items-center justify-between p-4 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <h3 id="termsModalTitle" class="text-lg font-semibold text-gray-900">Terms</h3>
                <nav class="text-sm text-gray-500">
                    <button type="button" onclick="showTermsTab('terms')" id="termsTabBtn" class="px-3 py-1 text-amber-400">Terms</button>
                    <button type="button" onclick="showTermsTab('privacy')" id="privacyTabBtn" class="px-3 py-1">Privacy</button>
                </nav>
            </div>
        </div>

        <div class="p-6 max-h-[70vh] overflow-y-auto bg-white text-gray-800" id="termsModalBody">
            <div id="termsContent">
                <h4 class="font-semibold mb-2">Terms of Service</h4>
                <p class="text-sm text-gray-600">By using Lumina, you agree to our service rules for orders, payments, returns, and account usage.</p>
                <p class="mt-3 text-sm text-gray-600"><b>Applicable Law</b><br>These terms are governed by Philippine laws, including RA 7394 (Consumer Act) and RA 8792 (E-Commerce Act).</p>
                <p class="mt-3 text-sm text-gray-600"><b>Customer Rights</b><br>Customers are entitled to clear product information, fair complaint handling, and return/refund processing under applicable policies and law.</p>
                <p class="mt-3 text-sm text-gray-600"><b>Orders and Returns</b><br>Orders may be reviewed for stock, pricing, and fraud checks. Returns and refunds are subject to the posted return conditions.</p>
                <p class="mt-4 text-xs text-gray-500">
                    Read the complete Terms:
                    <a href="{{ route('legal.terms') }}" target="_blank" rel="noopener" class="text-amber-600 underline">Terms of Service</a>
                </p>
            </div>

            <div id="privacyContent" class="hidden">
                <h4 class="font-semibold mb-2">Privacy Policy</h4>
                <p class="text-sm text-gray-600">Lumina collects and processes personal data needed to provide your account, checkout, delivery, and support services.</p>
                <p class="mt-3 text-sm text-gray-600"><b>Applicable Law</b><br>Personal data is processed in line with RA 10173 (Data Privacy Act of 2012), its IRR, and NPC issuances.</p>
                <p class="mt-3 text-sm text-gray-600"><b>Your Privacy Rights</b><br>You have rights such as the right to be informed, access, correct, object, erase/block (when applicable), and data portability.</p>
                <p class="mt-3 text-sm text-gray-600"><b>Data Sharing</b><br>We do not sell your personal data. Limited sharing is done only with service providers like payment and delivery partners to complete transactions.</p>
                <p class="mt-4 text-xs text-gray-500">
                    Read the complete Privacy Policy:
                    <a href="{{ route('legal.privacy') }}" target="_blank" rel="noopener" class="text-amber-600 underline">Privacy Policy</a>
                </p>
            </div>
        </div>

        <div class="p-4 border-t border-gray-100 flex items-center justify-between">
            <div>
                <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                    <input id="modalAgreeCheckbox" type="checkbox" class="w-4 h-4 text-amber-400 rounded" />
                    <span>I agree to the selected document</span>
                </label>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" onclick="acceptTermsFromModal()" class="px-4 py-2 rounded-xl bg-amber-300 text-black font-semibold">Agree & Continue</button>
            </div>
        </div>
    </div>
</div>

<script>
    function openTermsModal(tab) {
        document.getElementById('termsModal').classList.remove('hidden');
        showTermsTab(tab || 'terms');
        // ensure checkbox is cleared
        const cb = document.getElementById('modalAgreeCheckbox'); if(cb) cb.checked = false;
    }
    function closeTermsModal() {
        document.getElementById('termsModal').classList.add('hidden');
    }
    function showTermsTab(tab) {
        const terms = document.getElementById('termsContent');
        const privacy = document.getElementById('privacyContent');
        const tbtn = document.getElementById('termsTabBtn');
        const pbtn = document.getElementById('privacyTabBtn');
        if(tab === 'privacy'){
            privacy.classList.remove('hidden'); terms.classList.add('hidden');
            pbtn.classList.add('text-amber-400'); tbtn.classList.remove('text-amber-400');
            document.getElementById('termsModalTitle').textContent = 'Privacy Policy';
        } else {
            terms.classList.remove('hidden'); privacy.classList.add('hidden');
            tbtn.classList.add('text-amber-400'); pbtn.classList.remove('text-amber-400');
            document.getElementById('termsModalTitle').textContent = 'Terms of Service';
        }
    }

    function acceptTermsFromModal(){
        const cb = document.getElementById('modalAgreeCheckbox');
        if(!cb || !cb.checked){
            alert('Please tick "I agree to the selected document" before continuing.');
            return;
        }
        // mark the register terms checkbox and close
        const regTerms = document.getElementById('terms');
        if (regTerms) {
            regTerms.checked = true;
            // Trigger the same listeners used by manual checkbox interaction.
            regTerms.dispatchEvent(new Event('input', { bubbles: true }));
            regTerms.dispatchEvent(new Event('change', { bubbles: true }));
        }
        closeTermsModal();
    }
</script>
