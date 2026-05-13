<div class="col-md-6 mb-3">
    <x-inputGroup title="name" type="text" name="name" value="{{ $employee->user->name ?? '' }}" :required="true"
        placeholder="enter_your_employee_name" />
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup title="password" type="password" name="password" value="" :required="true"
        placeholder="enter_your_employee_password" />
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup title="email_address" type="email" name="email" value="{{ $employee->user->email ?? '' }}"
        :required="true" placeholder="enter_your_employee_email_address" />
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup title="phone_number" type="text" name="phone" value="{{ $employee->user->phone ?? '' }}"
        :required="true" placeholder="enter_your_employee_phone_number" />
</div>
<div class="col-md-6 mb-3">
    <x-select name="role_name" title="role" :required="true" placeholder="select_a_option">
        @foreach ($roles as $role)
            <option
                {{ (isset($employee->user->roles[0]->name) && $employee->user->roles[0]->name == $role->name) || old('role_name') == $role->name ? 'selected' : '' }}
                value="{{ $role->name }}">{{ ucfirst($role->name) }}
            </option>
        @endforeach
    </x-select>
</div>
<div class="col-md-6 mb-3">
    <x-select name="department_id" title="department" :required="true" placeholder="select_a_option">
        @foreach ($departments as $department)
            <option
                {{ (isset($employee->department_id) && $employee->department_id == $department->id) || old('department_id') == $department->id ? 'selected' : '' }}
                value="{{ $department->id }}">{{ $department->name }}
            </option>
        @endforeach
    </x-select>
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup title="country" type="text" name="country" value="{{ $employee->country ?? '' }}" :required="false"
        placeholder="enter_your_employee_country" />
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup title="city" type="text" name="city" value="{{ $employee->city ?? '' }}" :required="false"
        placeholder="enter_your_employee_city" />
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup title="address" type="text" name="address" value="{{ $employee->address ?? '' }}"
        :required="false" placeholder="enter_your_employee_address" />
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup title="staff_id" type="text" name="staff_id" value="{{ $employee->staff_id ?? '' }}"
        :required="false" placeholder="enter_your_employee_staff_id" />
</div>
