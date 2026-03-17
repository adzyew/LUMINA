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
            <div>
                <button type="button" onclick="closeTermsModal()" class="text-gray-500 hover:text-gray-700">Close</button>
            </div>
        </div>

        <div class="p-6 max-h-[70vh] overflow-y-auto bg-white text-gray-800" id="termsModalBody">
            <div id="termsContent">
                {{-- Replace with real Terms content or include file --}}
                <h4 class="font-semibold mb-2">Terms of Service</h4>
                <p class="text-sm text-gray-600">Welcome to Lumina. <br>We respect your privacy and are committed to protecting your personal data. This Privacy Policy explains how we collect, use, and safeguard your information when you visit our website and use our services.</p>
                <p class="mt-3 text-sm text-gray-600"><b>1. Information We Collect</b> <br> When you create an account, place an order, or interact with our platform, we may collect the following information:</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li><p class="mt-3 text-sm text-gray-600"><b>Registration Data:</b> Personal Identification Information: Full name, email address, phone number, and delivery address.</p></li>
                    <li><p class="mt-3 text-sm text-gray-600"><b>Profile Data:</b> Profile photos (uploaded securely via our third-party provider, Cloudinary) and account passwords.</p></li>
                    <li><p class="mt-3 text-sm text-gray-600"><b>Transaction Data:</b> Details about payments, orders, returns, and items in your cart or wishlist.</p></li>
                </ul>
                <p class="mt-3 text-sm text-gray-600"><b>2. How We Use Your Information</b><br> We use the collected information for the following purposes:</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li><p class="mt-3 text-sm text-gray-600">To process and deliver your orders, including managing payments and returns.</p></li>
                    <li><p class="mt-3 text-sm text-gray-600">To manage and maintain your Lumina user account.</p></li>
                    <li><p class="mt-3 text-sm text-gray-600">To communicate with you regarding your orders, account updates, or customer support inquiries.</p></li>
                    <li><p class="mt-3 text-sm text-gray-600">To personalize your shopping experience and improve our website's functionality.</p></li>
                </ul>
                <p class="mt-3 text-sm text-gray-600"><b>3. Information Sharing</b> <br> We do not sell your personal data. We may share your information with trusted third parties solely for operating our business, such as:</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li><p class="mt-3 text-sm text-gray-600">Payment gateways to process secure transactions.</p></li>
                    <li><p class="mt-3 text-sm text-gray-600">Delivery and logistics partners to ship your orders.</p></li>
                    <li><p class="mt-3 text-sm text-gray-600">Cloud storage providers (e.g., Cloudinary) to host your profile and product images.</p></li>
                </ul>
                <p class="mt-3 text-sm text-gray-600"><b>4. Data Security</b> <br>We implement industry-standard security measures to protect your personal information from unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over the internet is 100% secure.</p>
                <p class="mt-3 text-sm text-gray-600"><b>5. Your Rights</b> <br> You have the right to access, correct, or update your personal information at any time through your Lumina Dashboard. You may also request the deletion of your account and associated data by contacting our support team.</p>

            </div>

            <div id="privacyContent" class="hidden">
                <h4 class="font-semibold mb-2">Privacy Policy</h4>
                <p class="text-sm text-gray-600">Welcome to Lumina. <br>By accessing our website and creating an account, you agree to comply with and be bound by the following Terms of Service.</p>
                <p class="mt-3 text-sm text-gray-600"><b>1. User Accounts</b></p>
                <ul class="list-disc pl-5 space-y-1">
                    <li><p class="mt-3 text-sm text-gray-600"><b>Eligibility:</b> You must provide accurate, current, and complete information (including your name, email, phone number, and delivery address) during the registration process.</p></li>
                    <li><p class="mt-3 text-sm text-gray-600"><b>Account Security:</b> You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account. You must notify us immediately of any unauthorized use of your account.</p></li>
                </ul>
                <p class="mt-3 text-sm text-gray-600"><b>2. Products and Pricing</b></p>
                <ul class="list-disc pl-5 space-y-1">
                    <li><p class="mt-3 text-sm text-gray-600"><b>Product Descriptions:</b> We strive to ensure that all descriptions, images, and prices of our jewelry (rings, necklaces, earrings, bracelets, watches) are accurate. However, we do not warrant that product descriptions or other content are error-free.</p></li>
                    <li><p class="mt-3 text-sm text-gray-600"><b>Pricing:</b> All prices are subject to change without notice. We reserve the right to modify or discontinue products at any time.</p></li>
                </ul>
                <p class="mt-3 text-sm text-gray-600"><b>3. Orders and Payments</b></p>
                <ul class="list-disc pl-5 space-y-1">
                    <li><p class="mt-3 text-sm text-gray-600"><b>Order Acceptance:</b> We reserve the right to refuse or cancel any order for any reason, including limitations on available stock, inaccuracies in product or pricing information, or suspected fraud.</p></li>
                    <li><p class="mt-3 text-sm text-gray-600"><b>Shipping and Delivery:</b> Delivery times are estimates. Lumina is not liable for delays caused by third-party logistics providers.</p></li>
                </ul>
                <p class="mt-3 text-sm text-gray-600"><b>4. Returns and Refunds</b> <br> We want you to be satisfied with your Lumina purchase. Return requests must be submitted through your user dashboard within 7 days of receiving your item. Items must be unworn, in their original condition, and include all original packaging. Approved returns will be processed according to our standard refund procedures.</p>
                <p class="mt-3 text-sm text-gray-600"><b>5. Intellectual Property</b> <br> All content on this website, including logos, text, graphics, and images, is the property of Lumina and is protected by intellectual property laws. You may not reproduce, distribute, or create derivative works from our content without explicit permission.</p>
                <p class="mt-3 text-sm text-gray-600"><b>6. Changes to Terms</b> <br> We reserve the right to update these Terms of Service at any time. Changes will take effect immediately upon posting to the website. Your continued use of the platform constitutes your acceptance of the revised terms.</p>

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
                <button type="button" onclick="closeTermsModal()" class="px-4 py-2 rounded-xl bg-gray-100 text-gray-700 border border-gray-200 hover:bg-gray-200 transition-colors">Cancel</button>
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
        if(regTerms) regTerms.checked = true;
        closeTermsModal();
    }
</script>
