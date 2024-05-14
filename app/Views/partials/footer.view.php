</main>
<footer class="bg-white py-4 pb-5">
    <div class="container d-flex flex-column flex-sm-row border-top pt-2">
        <div class="flex-grow-1">
            &copy; <?= date('Y'); ?> - <span class="d-none d-md-inline">Ein Projekt von</span> <a
                    href="https://ismael-murad.com" target="_blank">Ismael Murad</a>
        </div>
        <div class="mt-4 mt-sm-0">
            <a href="datenschutz">Datenschutz</a> |
            <a href="impressum">Impressum</a>
        </div>
    </div>
</footer>

<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')

    if (tooltipTriggerList.length) {
        const tooltipList = [
            ...tooltipTriggerList
        ].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    }
</script>
</body>
</html>
