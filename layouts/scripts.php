<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-dismiss="alert"]').forEach(function (button) {
            button.addEventListener('click', function () {
                var target = button.closest('.alert');
                if (target) {
                    target.remove();
                }
            });
        });
    });
</script>
