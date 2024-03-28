<div class="modal fade" id="stateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add State</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="state_form" method="post" onsubmit="return false">
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="country_id">Country Name:</label>
                            <select class="form-select" aria-label="Default select example" id="country_id" name="country_id">
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="state_name">State Name:</label>
                            <input type="hidden" name="hid" id="hid" value="">
                            <input type="text" class="form-control" id="state_name" placeholder="State Name" name="state_name">
                        </div>
                        <div class="mb-3 ">
                            <label for="state_name">State Status:</label>

                            <select class="form-select" aria-label="Default select example" id="state_status" name="state_status">
                                <option selected disabled>Select State Status</option>
                                <option value="0">Active</option>
                                <option value="1">In-Active</option>
                            </select>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="Submit_state">Submit</button>
                </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
