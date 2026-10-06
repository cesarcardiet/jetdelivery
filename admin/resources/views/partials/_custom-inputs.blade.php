 @if (isset($page_data) && count($page_data) > 0)
    <div class="card card-body p-3 p-sm-4 mb-4">
        <h4 class="text-capitalize font-medium mb-3">
            {{ translate('messages.Additional_Data') }}
        </h4>
        <div class="row g-3">
            @foreach (data_get($page_data, 'data', []) as $key => $item)
                @if (!in_array($item['field_type'], ['file', 'check_box']))
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label"
                                    for="{{ $item['input_data'] }}">{{ translate($item['input_data']) }}

                                @if ($item['is_required'] == 1)
                                    <small class="text-danger">
                                        *</small>

                                @endif

                            </label>
                            <input id="{{ $item['input_data'] }}"
                                    {{ $item['is_required'] == 1 ? 'required' : '' }}
                                    data-field-name="{{ translate($item['input_data']) }}"
                                    type="{{ $item['field_type'] }}"
                                    name="additional_data[{{ $item['input_data'] }}]"
                                    class="form-control h--45px"
                                    placeholder="{{ translate($item['placeholder_data']) }}"
                                    value="">
                        </div>
                    </div>
                @elseif ($item['field_type'] == 'check_box')
                    @if ($item['check_data'] != null)
                        <div class="col-md-4">
                            <div class="form-group">
                                <label  class="form-label" for=""> {{ translate($item['input_data']) }}
                                    @if ($item['is_required'] == 1)
                                        <small class="text-danger">
                                            *</small>
                                    @endif
                                </label>
                                @foreach ($item['check_data'] as $k => $i)
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox"
                                                    name="additional_data[{{ $item['input_data'] }}][]"
                                                    class="form-check-input"
                                                    value="{{ $i }}">
                                            {{ translate($i) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @elseif ($item['field_type'] == 'file')
                    @if ($item['media_data'] != null)
                            <?php
                            $image = '';
                            $pdf = '';
                            $docs = '';
                            if (data_get($item['media_data'], 'image', null)) {
                                $image = '.jpg, .jpeg, .png,';
                            }
                            if (data_get($item['media_data'], 'pdf', null)) {
                                $pdf = ' .pdf,';
                            }
                            if (data_get($item['media_data'], 'docs', null)) {
                                $docs = ' .doc, .docs, .docx';
                            }
                            $accept = $image.$pdf.$docs;
                            ?>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="form-label"
                                        for="{{ $item['input_data'] }}">{{ translate($item['input_data']) }}
                                    @if ($item['is_required'] == 1)
                                        <small class="text-danger">
                                            *</small>

                                    @endif

                                </label>
                                <input id="{{ $item['input_data'] }}"
                                        {{ $item['is_required'] == 1 ? 'required' : '' }}
                                        data-field-name="{{ translate($item['input_data']) }}"
                                        type="{{ $item['field_type'] }}"
                                        name="additional_documents[{{ $item['input_data'] }}][]"
                                        class="form-control h--45px"
                                        placeholder="{{ translate($item['placeholder_data']) }}"
                                        {{ data_get($item['media_data'], 'upload_multiple_files', null) == 1 || data_get($item['media_data'], 'file_upload_quantity', null) > 1 ? 'multiple' : '' }}
                                        data-max-files="{{ data_get($item['media_data'], 'file_upload_quantity', 1) }}"
                                        data-max-size="{{ defined('MAX_FILE_SIZE') ? MAX_FILE_SIZE : 2 }}"
                                        accept="{{ $accept ?? '.jpg, .jpeg, .png' }}">
                            </div>
                        </div>
                    @endif
                @endif
            @endforeach


        </div>
    </div>
@endif


@push('script_2')
        <script>
        $(document).on('change', 'input[name^="additional_documents"]', function() {
            const maxFiles = parseInt($(this).data('max-files')) || 1;
            const maxSize = parseInt($(this).data('max-size')) || 2; // Default 2MB
            const maxSizeBytes = maxSize * 1024 * 1024;
            const files = this.files;

            if (files.length > maxFiles) {
                toastr.error(`{{ translate('You can upload a maximum of') }} ${maxFiles} {{ translate('files') }}.`);
                $(this).val(''); // Clear the input
                return;
            }

            for (let i = 0; i < files.length; i++) {
                if (files[i].size > maxSizeBytes) {
                    toastr.error(`{{ translate('File size must be less than') }} ${maxSize}MB.`);
                    $(this).val(''); // Clear the input
                    return;
                }
            }
        });
        </script>
@endpush