<button class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#edit-customer-name-modal">Edit</button>

<div class="modal fade"
    id="edit-customer-name-modal"
    tabindex="-1"
    ole="dialog"
    aria-labelledby="edit-customer-name-modal-label"
    aria-hidden="true"
>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <div class="modal-title" id="edit-customer-name-modal-label">Change Customer Name</div>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>

            <form action="/customers/{{ $customer->id }}" method="post">
                @csrf
                @method('PATCH')

                <div class="modal-body">
                    <div class="form-group">
                        <label for="company_name">Company Name</label>
                        <input type="text" class="form-control form-control-sm" name="company_name" value="{{ $customer->company_name }}">
                    </div>
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" class="form-control form-control-sm" name="first_name" value="{{ $customer->first_name }}">
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control form-control-sm" name="last_name" value="{{ $customer->last_name }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>

        </div>
    </div>
</div>
