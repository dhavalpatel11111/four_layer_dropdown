<div class="modal fade" id="countryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add Country</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="country_form" method="post" onsubmit="return false" name="country_form">
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3 mt-3">
                            <label for="text">Country Name:</label>
                            <input type="hidden" name="hid" id="hid" value="">
                            <input type="text" class="form-control" id="country_name" placeholder="Country Name" name="country_name" required>
                        </div>
                        <div class="mb-3 ">
                            <select class="form-select" aria-label="Default select example" id="country_status" name="country_status" required>
                                <option selected disabled>Select Country Status</option>
                                <option value="0">Active</option>
                                <option value="1">In-Active</option>
                            </select>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="submit_country">Submit</button>
                </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>