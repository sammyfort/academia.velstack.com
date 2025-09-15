@section('title', "Create Expense")
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form wire:submit.prevent="create">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Create Expense</h5>
                    <div class="d-flex gap-2">

                        <x-link label="Expenses" route="expenses.index" class="btn btn-info add-bt " icon="ri-grid-fill me-1 align-bottom"/>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <x-input-text label="Description" model="expense.description"/>
                        <x-enum-select enum="App\Enum\ExpenseCategory" label="Category" model="expense.category"/>
                        <x-input-text label="Amount" model="expense.amount" type="number" step="0.0001"/>
                        <x-input-text label="Disbursement Date" type="date" model="expense.expense_date"/>
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
