<span data-role="launch-modal" data-toggle="modal" data-target="#comment-message"></span>

<div class="modal fade" id="comment-message" tabindex="-1" data-role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i class="fa fa-times"></i></span><span class="sr-only">Закрыть</span></button>
                <h4 class="modal-title"><?php echo $title; ?></h4>
            </div>
            <div class="modal-body"><?php echo $content; ?></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Ок, закрыть</button>
            </div>
        </div>
    </div>
</div>