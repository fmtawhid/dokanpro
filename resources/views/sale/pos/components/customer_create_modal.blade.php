<div class="fixed z-10 inset-0 invisible overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true"
    id="customerAddModal">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>
        <div
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            {{ __('add_customer') }}
                        </h3>

                        <div class="grid md:grid-cols-2 gap-4 py-3 w-full">
                            <div>
                                <label class="text-slate-500" for="customer_group_id">{{ __('customer_group') }}</label>
                                <select id="customer_group_id"
                                    class="border-slate-200 bg-slate-50 rounded-lg text-slate-400 focus:ring-slate-300 focus:border-transparent focus:ring-1 w-full mt-2">
                                    <option value="" selected disabled>
                                        {{ __('select_a_option') }}
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label class="text-slate-500" for="name">{{ __('name') }}<span
                                        class="text-red-500">*</span></label>
                                <input type="text" id="name"
                                    class="border-slate-200 bg-slate-50 rounded-lg placeholder:text-slate-400 focus:ring-slate-300 focus:border-transparent focus:ring-1 w-full mt-2"
                                    placeholder="{{ __('enter_your_customer_name') }}">
                            </div>
                            <div>
                                <label class="text-slate-500" for="phone_number">{{ __('phone_number') }}<span
                                        class="text-red-500">*</span></label>
                                <input type="number" id="phone_number"
                                    class="border-slate-200 bg-slate-50 rounded-lg placeholder:text-slate-400 focus:ring-slate-300 focus:border-transparent focus:ring-1 w-full mt-2"
                                    placeholder="{{ __('enter_your_customer_phone_number') }}" />
                            </div>
                            <div>
                                <label class="text-slate-500" for="email">{{ __('email') }}</label>
                                <input type="email" id="email"
                                    class="border-slate-200 bg-slate-50 rounded-lg placeholder:text-slate-400 focus:ring-slate-300 focus:border-transparent focus:ring-1 w-full mt-2"
                                    placeholder="{{ __('enter_your_customer_email_address') }}" />
                            </div>
                            <div>
                                <label class="text-slate-500" for="password">{{ __('password') }}</label>
                                <input type="password" id="password"
                                    placeholder="{{ __('enter_your_customer_password') }}"
                                    class="border-slate-200 bg-slate-50 rounded-lg placeholder:text-slate-400 focus:ring-slate-300 focus:border-transparent focus:ring-1 w-full mt-2">
                            </div>
                            <div>
                                <label class="text-slate-500" for="tax_number">{{ __('tax_number') }}</label>
                                <input type="text" id="tax_number"
                                    class="border-slate-200 bg-slate-50 rounded-lg placeholder:text-slate-400 focus:ring-slate-300 focus:border-transparent focus:ring-1 w-full mt-2"
                                    placeholder="{{ __('enter_your_customer_tax_number') }}" />
                            </div>
                            <div class="col-span-2">
                                <label class="text-slate-500" for="address">{{ __('address') }}</label>
                                <input type="text" id="address"
                                    class="border-slate-200 bg-slate-50 rounded-lg placeholder:text-slate-400 focus:ring-slate-300 focus:border-transparent focus:ring-1 w-full mt-2"
                                    placeholder="{{ __('enter_your_customer_address') }}" />
                            </div>
                            <div>
                                <label class="text-slate-500" for="country">{{ __('country') }}</label>
                                <input type="text" id="country"
                                    class="border-slate-200 bg-slate-50 rounded-lg placeholder:text-slate-400 focus:ring-slate-300 focus:border-transparent focus:ring-1 w-full mt-2"
                                    placeholder="{{ __('enter_your_customer_country') }}" />
                            </div>
                            <div>
                                <label class="text-slate-500" for="city">{{ __('city') }}</label>
                                <input type="text" id="city"
                                    class="border-slate-200 bg-slate-50 rounded-lg placeholder:text-slate-400 focus:ring-slate-300 focus:border-transparent focus:ring-1 w-full mt-2"
                                    placeholder="{{ __('enter_your_customer_city') }}" />
                            </div>
                            <div>
                                <label class="text-slate-500" for="state">{{ __('state') }}</label>
                                <input type="text" id="state"
                                    class="border-slate-200 bg-slate-50 rounded-lg placeholder:text-slate-400 focus:ring-slate-300 focus:border-transparent focus:ring-1 w-full mt-2"
                                    placeholder="{{ __('enter_your_customer_state') }}" />
                            </div>
                            <div>
                                <label class="text-slate-500" for="post_code">{{ __('post_code') }}</label>
                                <input type="text" id="post_code"
                                    class="border-slate-200 bg-slate-50 rounded-lg placeholder:text-slate-400 focus:ring-slate-300 focus:border-transparent focus:ring-1 w-full mt-2"
                                    placeholder="{{ __('enter_your_customer_post_code') }}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="submitCustomer"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    {{ __('submit') }}
                </button>
                <button type="button"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                    id="closeModalCustomer">
                    {{ __('close') }}
                </button>
            </div>
        </div>
    </div>
</div>
