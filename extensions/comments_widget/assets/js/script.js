$(document).ready( function(){

    var colorSuccess = "#baefba",
        colorEdit = "#ED9C28";

    /**
     * Анимирует комментарий
     * @param color цвет
     * @param id id комментария
     */
    function animateComment(id, color){
        var originalBG =  $('.comment[data-id="' + id + '"]').css("backgroundColor");
        $('.comment[data-id="' + id + '"]')
            .animate({"backgroundColor": color}, 500)
            .animate({"backgroundColor": originalBG}, 800);
    }

    /**
     * Загружает форму (форма ответа или редактирования)
     * @param elem элемент, по которму кликнули
     * @param url урл, с которого получаем форму
     */
    function loadForm(elem, url){
        var id = elem.data('id'),
            form = elem.parents('.comment').find('[role="dynamic-form-container"]'),
            btnGroup = elem.parents('.comment').find('.btn-group');

        $.ajax({
            "url": url,
            "type": "post",
            "dataType": "html",
            "data": "id=" + id + "&url=" + pageUrl,
            "success": function(data) {
                form.html(data);
                btnGroup.hide();
            }
        });
    }

    /**
     * Создаёт комментарий по аяксу
     * @param form
     */
    function ajaxCreate(form)
    {
        $.ajax({
            "url": commentsCreateUrl,
            "type": "post",
            "dataType": "json",
            "data": form.serialize(),
            "success": function(data) {
                // Сбрасываем форму
                form[0].reset();
                // Перерисовываем дерево
                $('[role="tree"]').html(data.tree);

                // Если включена премодераця, то показываем сообщение и выходим
                if (data.premoderate){
                    $('[role="modal-container"]').html(data.modal);
                    $('[role="launch-modal"]').click();
                    return;
                }

                // Скроллимся к добавленному комментарию
                $.scrollTo('[name="comment_' + data.id + '"]', 1000);
                // Анимируем добавленный комментарий
                animateComment(data.id, colorSuccess);
                // Обновляем счётчик комментариев
                $('[role="count"]').html(data.count);
            },
            "beforeSend": function() {
                $(".comments button").attr("disabled", true);
            },
            "complete": function() {
                // Включаем кнопки
                $(".comments button").attr("disabled", false);
            }
        });
    }

    /**
     * Обновляет комментарий по аяксу
     * @param form
     */
    function ajaxUpdate(form)
    {
        $.ajax({
            "url": commentsUpdateUrl,
            "type": "post",
            "dataType": "json",
            "data": form.serialize(),
            "success": function(data) {
                // Перерисовываем дерево
                $('[role="tree"]').html(data.tree);
                // Скроллимся к добавленному комментарию
                $.scrollTo('[name="comment_' + data.id + '"]', 1000);
                // Анимируем добавленный комментарий
                animateComment(data.id, colorEdit);
            },
            "beforeSend": function() {
                $(".comments button").attr("disabled", true);
            },
            "complete": function() {
                // Включаем кнопки
                $(".comments button").attr("disabled", false);
            }
        });
    }

    // Обрабатываем клик по кнопке "ответ"
    $(".comments").on("click", '[href="#comment_reply"]', function(){
        loadForm($(this), commentsReplyFormUrl);
    });

    // Обрабатываем клик по кнопке "редактировать"
    $(".comments").on("click", '[href="#comment_edit"]', function(){
        loadForm($(this), commentsUpdateFormUrl);
    });

    // Обрабатываем клик по кнопке "отмена"
    $(".comments").on("click", '[role="cancel"]', function(e){
        e.preventDefault();
        $(this).parents('.comment').find('.btn-group').show();
        $(this).parents('.comment').find('[role="dynamic-form-container"]').empty();
    });

    // Обрабатываем отправку форму и вызываем либо обновление, либо создание нового комментария
    $(".comments").on("submit", "form", function(e){
        e.preventDefault();
        if ( $(this).find('[data-is-new]').data('is-new') )
            ajaxCreate( $(this) );
        else
            ajaxUpdate( $(this) );
    });

    // Обрабатываем удаление комментария
    $(".comments").on("click", '[href="#comment_delete"]', function(e){
        e.preventDefault();

        $.ajax({
            "url": commentsDeleteUrl,
            "type": "post",
            "dataType": "json",
            "data": "id=" + $(this).data('id'),
            "success": function(data) {
                // Перерисовываем дерево
                $('[role="tree"]').html(data.tree);
                // Обновляем счётчик комментариев
                $('[role="count"]').html(data.count);
                // Обновляем контейнер с сообщением и показываем сообщение
                $('[role="modal-container"]').html(data.modal);
                $('[role="launch-modal"]').click();
            },
            "beforeSend": function() {
                $(".comments button").attr("disabled", true);
            },
            "complete": function() {
                // Включаем кнопки
                $(".comments button").attr("disabled", false);
            }
        });
    });

    // Обрабатываем клик по лайку
    $(".comments").on("click", '[href="#comment_like"]', function(e){
        e.preventDefault();
        var likes = $(this).parents(".comment").find('[role="likes"]'),
            like = $(this).data("like"),
            id = $(this).parents(".comment").data("id");

        $.ajax({
            "url": commentsLikesUrl,
            "type": "post",
            "dataType": "json",
            "data": "id=" + id + "&like=" + like,
            "success": function(data) {
                // Обновляем значение лайков
                likes.text(data.likes);
            }
        });
    });
});