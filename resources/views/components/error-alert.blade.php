@props([
    'type' => 'error',
    'title' => null,
    'message' => null,
    'suggestions' => [],
    'dismissible' => true,
    'autoHide' => false,
    'hideDelay' => 5000
])

@php
    $alertClasses = match($type) {
        'error' => 'bg-red-50 border-red-200 text-red-800',
        'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
        'info' => 'bg-blue-50 border-blue-200 text-blue-800',
        'success' => 'bg-green-50 border-green-200 text-green-800',
        default => 'bg-gray-50 border-gray-200 text-gray-800'
    };
    
    $iconClasses = match($type) {
        'error' => 'text-red-400',
        'warning' => 'text-yellow-400',
        'info' => 'text-blue-400',
        'success' => 'text-green-400',
        default => 'text-gray-400'
    };
    
    $icon = match($type) {
        'error' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z',
        'warning' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z',
        'info' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'success' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        default => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
    };
@endphp

<div 
    x-data="errorAlert()" 
    x-init="init()"
    class="rounded-md border p-4 {{ $alertClasses }}"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform scale-95"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-95"
>
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 {{ $iconClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
            </svg>
        </div>
        <div class="ml-3 flex-1">
            @if($title)
                <h3 class="text-sm font-medium">
                    {{ $title }}
                </h3>
            @endif
            
            @if($message)
                <div class="mt-2 text-sm">
                    <p>{{ $message }}</p>
                </div>
            @endif
            
            @if(count($suggestions) > 0)
                <div class="mt-3">
                    <div class="text-sm">
                        <p class="font-medium">Here are some things you can try:</p>
                        <ul class="mt-2 list-disc list-inside space-y-1">
                            @foreach($suggestions as $suggestion)
                                <li>{{ $suggestion }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            
            @if($dismissible)
                <div class="mt-4">
                    <div class="-mx-2 -my-1.5 flex">
                        <button 
                            @click="dismiss()"
                            type="button" 
                            class="inline-flex rounded-md p-1.5 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 focus:ring-offset-red-50"
                        >
                            <span class="sr-only">Dismiss</span>
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function errorAlert() {
    return {
        show: true,
        init() {
            @if($autoHide)
                setTimeout(() => {
                    this.dismiss();
                }, {{ $hideDelay }});
            @endif
        },
        dismiss() {
            this.show = false;
            // Remove from DOM after animation
            setTimeout(() => {
                this.$el.remove();
            }, 200);
        }
    }
}
</script>
