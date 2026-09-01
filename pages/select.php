<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <title>Multiselect Dropdown</title>
</head>

<body class="bg-gray-100 p-10">




  <script>
    $(document).ready(function() {

      // Toggle dropdown
      $('#filterHari').on('click', function(e) {
        e.stopPropagation();
        $('#listHari').toggleClass('hidden');
      });


      // Update text & value
      function updateSelected() {

        let selected = [];

        $('.option-checkbox:checked').each(function() {
          selected.push({
            value: $(this).val(),
            text: $(this).closest('label').find('span').text().trim()
          });
        });

        // Update button text
        if (selected.length === 0) {
          $('#selectedText')
            .text('Pilih hari...')
            .removeClass('text-gray-900')
            .addClass('text-gray-500');

        } else {

          $('#selectedText')
            .text(selected.map(item => item.text).join(', '))
            .removeClass('text-gray-500')
            .addClass('text-gray-900');
        }

        // Update result
        $('#result').text(
          JSON.stringify(
            selected.map(item => item.value),
            null,
            2
          )
        );
      }


      // Checkbox berubah
      $('.option-checkbox').on('change', function() {
        updateSelected();
      });


      // Select All
      $('#selectAll').on('click', function() {
        $('.option-checkbox').prop('checked', true);
        updateSelected();
      });


      // Clear All
      $('#clearAll').on('click', function() {
        $('.option-checkbox').prop('checked', false);
        updateSelected();
      });

      // Klik di luar dropdown
      $(document).on('click', function(e) {

        if (!$(e.target).closest('#multiselect').length) {
          $('#listHari').addClass('hidden');
        }

      });

      // Initial state
      updateSelected();

    });
  </script>

</body>

</html>
