<div class="fixed z-10 inset-0 invisible overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true"
    id="storeWalletModal">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>
        <div
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            {{ __('') }}Deposit POS Cash
                        </h3>

                        <div class="mt-2">
                            <div class="flex justify-between flex-wrap gap-2 bg-slate-100 p-3 rounded-lg">
                                <div class="text-lg">
                                    <span class="text-slate-500">Available Amount :
                                    </span>
                                    <span class="primary-text-color font-semibold">$ 100.00</span>
                                </div>
                                <div class="text-lg">
                                    <span class="text-slate-500">Today's Sale :
                                    </span>
                                    <span class="primary-text-color font-semibold">$ 100.00</span>
                                </div>
                            </div>
                            <div class="grid gap-4 md:grid-cols-2 mt-2 w-full">
                                <div class="mt-3">
                                    <label class="text-slate-500" for="payment_method">Payment Method<span
                                            class="text-red-500">*</span></label>
                                    <select id="payment_method"
                                        class="border-slate-200 bg-slate-50 rounded-lg text-slate-400 focus:ring-slate-300 focus:border-transparent focus:ring-1 w-full mt-2">
                                        <option value="Cash">
                                            Cash
                                        </option>
                                        <option value="Bank">
                                            Bank
                                        </option>
                                    </select>
                                </div>
                                <div class="mt-3">
                                    <label class="text-slate-500" for="account_id">Account<span
                                            class="text-red-500">*</span></label>
                                    <select id="account_id"
                                        class="border-slate-200 bg-slate-50 rounded-lg text-slate-400 focus:ring-slate-300 focus:border-transparent focus:ring-1 w-full mt-2">
                                        <option value="" selected disabled>
                                            Select a account
                                        </option>
                                        <option>account.name</option>
                                    </select>
                                </div>
                                <div class="mt-3">
                                    <label class="text-slate-500" for="amount">Amount
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" id="amount"
                                        class="border-slate-200 bg-slate-50 rounded-lg placeholder:text-slate-400 focus:ring-slate-300 focus:border-transparent focus:ring-1 w-full mt-2"
                                        placeholder="Enter amount" />
                                </div>
                            </div>
                            <div class="grid gap-4 md:grid-cols-2 mt-2 w-full">
                                <div class="mt-3">
                                    <label class="text-slate-500" for="purpose">Purpose</label>
                                    <input type="text" id="purpose"
                                        class="border-slate-200 bg-slate-50 rounded-lg placeholder:text-slate-400 focus:ring-slate-300 focus:border-transparent focus:ring-1 w-full mt-2"
                                        placeholder="Enter purpose" />
                                </div>
                                <div class="mt-3">
                                    <label class="text-slate-500" for="name">Date
                                        <span class="text-red-500">*</span></label>
                                    <input type="date" id="date"
                                        class="border-slate-200 bg-slate-50 rounded-lg placeholder:text-slate-400 text-slate-400 focus:ring-slate-300 focus:border-transparent focus:ring-1 w-full mt-2" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Submit
                </button>
                <button type="button"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                    id="closeModalWallet">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
