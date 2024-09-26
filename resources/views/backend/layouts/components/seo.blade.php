<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5>CẤU HÌNH SEO</h5>
        <div class="ibox-tools">
            <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="demo-seo">
                    <p class="title-seo">
                        Bạn chưa có tiêu đề SEO
                    </p>
                    <p class="style-canonical">
                        https://localhost:8000/<span class="canonical-seo">duong-dan-cua-ban</span>.html
                    </p>
                    <p class="description-seo">
                        Bạn chưa có mô tả SEO
                    </p>
                </div>
                <div class="seo form-group">
                    <div class="flex-space-between">
                        <label class="label-title">Tiêu đề SEO</label>
                        <label><span data-limit="60" data-target="title-seo" class="characterCounter">0</span> kí tự</label>
                    </div>
                    <input type="text"
                        value="{{ old('meta_title') ?? (isset($model) ? $model->meta_title : '') }}"
                        placeholder="Nhập tiêu đề SEO..." name="meta_title" data-type="meta_title"
                        class="form-control meta-seo">
                </div>
                <div class="seo form-group">
                    <div class="flex-space-between">
                        <label>Từ khóa SEO</label>
                        <label><span data-limit="60" data-target="keyword-seo" class="characterCounter">0</span> kí tự</label>
                    </div>
                    <textarea placeholder="Nhập từ khóa SEO..." name="meta_keyword" data-type="meta_keyword" class="form-control meta-seo">{{ old('meta_keyword') ?? (isset($model) ? $model->meta_keyword : '') }}</textarea>
                </div>
                <div class="seo form-group">
                    <div class="flex-space-between">
                        <label class="label-title">Mô tả SEO</label>
                        <label><span data-limit="160" data-target="description-seo" class="characterCounter">0</span> kí tự</label>
                    </div>
                    <textarea style="height: 150px" placeholder="Nhập mô tả SEO..." name="meta_description" data-type="meta_description"
                        class="form-control meta-seo">{{ old('meta_description', $model->meta_description ?? '') }}</textarea>
                </div>
                <div class="form-group">
                    <label class="label-title">Đường dẫn <span class="text-danger">(*)</span></label>
                    <input class="originalCanonical form-control" type="text" value="{{convertToSlug(old('canonical', $model->canonical ?? ''))}}" placeholder="Nhập canonical...">
                    <input type="hidden" value="{{convertToSlug(old('canonical', $model->canonical ?? ''))}}" placeholder="Nhập canonical..."
                        name="canonical" data-type="meta_canonical" class="form-control meta-seo">
                </div>
            </div>
        </div>
    </div>
</div>