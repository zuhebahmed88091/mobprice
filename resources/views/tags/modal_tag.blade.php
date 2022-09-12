<div class="modal fade" id="modal-tag">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Create New Tag</h4>
            </div>
            <div class="modal-body">

                <form method="POST" action="{{ route('tags.store', true) }}" id="formTag">
                    {{ csrf_field() }}

                    @include ('tags.form', ['tag' => null,])

                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary saveTag">Add Tag</button>
            </div>
        </div>
    </div>
</div>
