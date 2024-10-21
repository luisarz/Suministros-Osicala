@if (auth()->check() && optional(auth()->user()->employee->wherehouse)->logo)
    <img src="{{ asset('storage/' . auth()->user()->employee->wherehouse->logo) }}" alt="Brand Logo" class="h-auto max-w-full">
@else
    <img src="{{ asset('storage/default-logo.png') }}" alt="Default Logo" style="width: auto; height: auto;">
@endif
