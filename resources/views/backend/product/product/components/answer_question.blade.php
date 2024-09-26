<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5>CÂU HỎI THƯỜNG GẶP</h5>
        <div class="ibox-tools">
            <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <div class="ibox-content listQuestion">
        <span class="btn btn-primary addQuestion" style="width:100%"><i class="fa fa-plus"></i> Thêm câu hỏi</span>
        @php
            $questions = old('questions') ?? (isset($product) && $product->questions_answers !== null ? $product->questions_answers['questions'] : []);
            $answers = old('answers') ?? (isset($product) && $product->questions_answers !== null ? $product->questions_answers['answers'] : []);
        @endphp
        @if(count($questions) && count($answers))
        @foreach($questions as $key => $value)
            <div class="question-item">
                <div class="form-group">
                    <input type="text"
                        value="{{ $value }}"
                        placeholder="Nhập câu hỏi..." name="questions[]" class="form-control">
                </div>
                <div class="form-group">
                    <input type="text"
                        value="{{ $answers[$key] }}"
                        placeholder="Nhập câu trả lời..." name="answers[]" class="form-control">
                </div>
                <span class="deleteQuestion btn btn-danger"><i class="fa fa-times"></i></span>
            </div>
        @endforeach
        @endif
    </div>
</div>