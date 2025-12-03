@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-background-dark border-border text-text-primary focus:border-primary focus:ring-primary rounded-md shadow-sm']) }}>
