@section('title', "Create class")
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form wire:submit.prevent="create">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Create Class</h5>
                    <div class="d-flex gap-2">

                        <x-link label="Classes" route="classes.index" class="btn btn-info add-bt " icon="ri-grid-fill me-1 align-bottom"/>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <x-input-text label="Class Name" model="class.name" required/>
                        <x-enum-select enum="App\Enum\ClassLevel" label="Level" model="class.level" required/>
                        <x-enum-select enum="App\Enum\ClassGroup" label="Base Class" model="class.group" required/>
                        <x-input-text label="Slug" model="class.slug"/>

                        <div class="col-lg-12">
                            <div class="hstack justify-content-end gap-2">
                                <x-button label="Create"/>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@section('script')
    <script src="{{URL::asset('build/js/app.js')}}"></script>
@endsection
