@props(['centered' => false])

<footer {{ $attributes->class([
    'small text-secondary',
    'text-center' => $centered,
    'px-2' => ! $centered,
]) }}>
    <p class="mb-1">
        <a class="link-muted" href="#">About</a> ·
        <a class="link-muted" href="#">Privacy</a> ·
        <a class="link-muted" href="#">Terms</a> ·
        <a class="link-muted" href="#">Cookies</a> ·
        <a class="link-muted" href="#">Help</a>
    </p>
    <p class="mb-0">© 2026 YouBee Social</p>
</footer>
