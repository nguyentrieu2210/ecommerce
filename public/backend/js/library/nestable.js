$(document).ready(function() {
    function updateOutput(e) {
        var list = e.length ? e : $(e.target),
            output = list.data('output');
        if (window.JSON) {
            output.val(window.JSON.stringify(list.nestable('serialize')));
        } else {
            output.val('JSON browser support required for this demo.');
        }
    }

    function initializeNestable() {
        $('#nestable2').nestable({
            group: 1
        }).on('change', updateOutput);

        // Xuất dữ liệu ban đầu đã được serialize
        updateOutput($('#nestable2').data('output', $('#nestable2-output')));
    }

    function handleNestableMenuClick(e) {
        var target = $(e.target),
            action = target.data('action');
        if (action === 'expand-all') {
            $('.dd').nestable('expandAll');
        }
        if (action === 'collapse-all') {
            $('.dd').nestable('collapseAll');
        }
    }

    function initializeNestableMenu() {
        $('#nestable-menu').on('click', handleNestableMenuClick);
    }

    // Khởi tạo các chức năng
    initializeNestable();
    initializeNestableMenu();
});


// $(document).ready(function() {
//     function updateOutput(e) {
//         var list = e.length ? e : $(e.target),
//             output = list.data('output');
//         if (window.JSON) {
//             var serializedData = list.nestable('serialize');
//             output.val(window.JSON.stringify(serializedData, null, 2));
//             console.log(serializedData);
//         } else {
//             output.val('JSON browser support required for this demo.');
//         }
//     }

//     // Activate Nestable for list
//     $('#nestable2').nestable().on('change', updateOutput);

//     // Output initial serialized data
//     updateOutput($('#nestable2').data('output', $('#output')));
// });
