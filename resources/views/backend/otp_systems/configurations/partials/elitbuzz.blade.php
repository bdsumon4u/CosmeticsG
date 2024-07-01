<div class="col-lg-6">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('ELITBUZZ Credential')}}</h5>
        </div>
        <div class="card-body">
            <form class="form-horizontal" action="{{ route('update_credentials') }}" method="POST">
                <input type="hidden" name="otp_method" value="elitbuzz">
                @csrf
                <div class="form-group row">
                    <input type="hidden" name="types[]" value="ELITBUZZ_API_KEY">
                    <div class="col-lg-3">
                        <label class="col-from-label">{{translate('ELITBUZZ_API_KEY')}}</label>
                    </div>
                    <div class="col-lg-6">
                        <input type="text" class="form-control" name="ELITBUZZ_API_KEY" value="{{  env('ELITBUZZ_API_KEY') }}" placeholder="ELITBUZZ_API_KEY" required>
                    </div>
                </div>
                <div class="form-group row">
                    <input type="hidden" name="types[]" value="ELITBUZZ_USERNAME">
                    <div class="col-lg-3">
                        <label class="col-from-label">{{translate('ELITBUZZ_USERNAME')}}</label>
                    </div>
                    <div class="col-lg-6">
                        <input type="text" class="form-control" name="ELITBUZZ_USERNAME" value="{{  env('ELITBUZZ_USERNAME') }}" placeholder="ELITBUZZ_USERNAME" required>
                    </div>
                </div>
                <div class="form-group row">
                    <input type="hidden" name="types[]" value="ELITBUZZ_USER_SENDER_ID">
                    <div class="col-lg-3">
                        <label class="col-from-label">{{translate('ELITBUZZ_USER_SENDER_ID')}}</label>
                    </div>
                    <div class="col-lg-6">
                        <input type="text" class="form-control" name="ELITBUZZ_USER_SENDER_ID" value="{{  env('ELITBUZZ_USER_SENDER_ID') }}" placeholder="ELITBUZZ_USER_SENDER_ID" required>
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>