@if(!empty($product->content))
<div class="information ">
    <h2 class="mb-[32px] pt-5 text-left text-HeadlineLarge font-bold text-dark-400 fs26">Thông tin sản phẩm</h2>
    <div class="flex w-full" data-headlessui-state="open">
        <div class="w-full">
            <div>
                <div class="descriptionProduct flex  flex-col items-start overflow-hidden max-h-[800px]">
                    <div class="main-description break-words w-full" id="description-main">
                        {!! $product->content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex w-full justify-center">
        <button
            class="actionContent moreContent mx-auto mt-[24px] flex w-max items-center gap-x-1 rounded border border-info-border px-5 py-2 text-center font-normal leading-6 text-product-salePrice hover:border-border-primary">Xem thêm 
        </button>
    </div>
</div>
@endif