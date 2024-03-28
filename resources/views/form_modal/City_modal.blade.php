<div class="modal fade" id="cityModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add City</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="city_form" method="post" onsubmit="return false">
                    <div class="modal-body">
                        <input type="hidden" name="hid" id="hid" value="">
                        @csrf
                        <div class="mb-3">
                            <label for="country_id">Country Name:</label>
                            <select class="form-select" aria-label="Default select example" id="country_id" name="country_id">
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="state_id">State Name:</label>
                            <select class="form-select" aria-label="Default select example" id="state_id" name="state_id">
                                <option selected disabled value="defult">Select State</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="state_name">City Name:</label>
                            <input type="text" class="form-control" id="city_name" placeholder="City Name" name="city_name">
                        </div>
                        <div class="mb-3 ">
                            <label for="state_name">City Status:</label>

                            <select class="form-select" aria-label="Default select example" id="city_status" name="city_status">
                                <option selected disabled>Select City Status</option>
                                <option value="0">Active</option>
                                <option value="1">In-Active</option>
                            </select>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="Submit_city">Submit</button>
                </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>