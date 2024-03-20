@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid mw-100">





        <div class="card w-100 position-relative overflow-hidden">
            <div class="card-body p-4">
                <form method="POST" class="container-fluid" action="{{ url('/Bundesland/update/' . $data->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-4 col-12">
                            <div class="mb-3">
                                <label for="bundsland" class="form-label">Bundesland</label>
                                <input type="text" class="form-control @error('bundsland') is-invalid @enderror"
                                    value="{{ $data->bundsland }}" name="bundsland" id="bundsland"
                                    aria-describedby="emailHelp">
                                @error('bundsland')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4 col-12">
                            <div class="mb-3">
                                <label for="presierhohung" class="form-label">Preiserhöhung in %</label>
                                <input type="text"
                                    class="form-control customInput @error('presierhohung') is-invalid @enderror"
                                    value="{{ $data->presierhohung }}" name="presierhohung" id="presierhohung"
                                    aria-describedby="presierhohung">
                                @error('presierhohung')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4 col-12">
                            <div class="mb-3">
                                <label for="meldung" class="form-label">Meldung</label>
                                <input type="text" class="form-control @error('meldung') is-invalid @enderror"
                                    value="{{ $data->meldung }}" name="meldung" id="meldung" aria-describedby="meldung">
                                @error('meldung')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-12">
                                <div class="mb-3">
                                    <label for="file" class="form-label">Preisliste Upload</label>
                                    <input type="file" class="form-control @error('file') is-invalid @enderror"
                                        value="{{ old('name') }}" name="file[]" id="file">
                                    @error('file')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-1 col-12 d-flex align-items-center">
                                <a href="javascript:;" class="btn-remove-row" class="text-danger"><i
                                        class="fa fa-times color-primary"></i></a>
                            </div>
                        </div>
                        <div class="more-fields"></div>
                        <div class="row">
                            <div class="col-lg-1">
                                <button type="button" class="btn btn-submit mt-3 add-more px-2 mb-5"><i
                                        class="fa fa-plus"></i>
                                    Add More</button>
                            </div>
                        </div>
                        <div class="col-md-12 mb-4 mt-3">
                            <button type="submit" class="btn btn-success me-2">speichern</button>
                            <button type="button" onclick="window.location.reload();"
                                class="btn btn-submit">Löschen</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card w-100 position-relative overflow-hidden">
            <div class="card-body p-4">
                <div class="row">
                    <h4 class="mb-3">Uploaded Files</h4>
                    @if (count($files) > 0)
                        @foreach ($files as $img)
                            <div class="col-lg-2">
                                <div class="card">
                                    @php
                                        $fileTypes = explode('/', $img->type);
                                        $types = $fileTypes[0];
                                        // dd($types);
                                    @endphp
                                    @if ($types == 'image')
                                        <img class="card-img-top img-responsive"
                                            src="{{ asset('public') }}/uploads/{{ $img->name }}">
                                    @elseif($types == 'application')
                                        <i class="fa fa-file fa-3x"
                                            style="text-align: center; padding-top: 30px; padding-bottom: 30px;"></i>
                                    @else
                                        <i class="fa fa-file fa-3x"></i>
                                    @endif
                                    <div class="card-body d-flex justify-content-between">
                                        <a href="{{ url('Delete/image') }}/{{ $img->id }}"
                                            class="btn btn-danger btn-sm"><i class="ti ti-trash"></i></a>
                                        <button data-url="{{ url('Edit/image') }}" data-id="{{ $img->id }}"
                                            class="btn btn-primary btn-sm uploadBtn"><i class="ti ti-edit"></i></button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
    <form id="imgUpdate" action="" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" id="fileInput" name="file" style="display: none;">
        <input type="hidden" id="update_id" name="update_id">
    </form>
@endsection
@section('javascript')
    <script>
        $(".uploadBtn").click(function() {
            var id = $(this).data('id');
            var url = $(this).data('url');
            $("#imgUpdate").attr("action", url);
            $("#update_id").val(id);
            $("#fileInput").click();
        });
        $("#fileInput").change(function() {
            $("#imgUpdate").submit();
        });
        $(document).on('click', '.add-more', function() {
            var html = `<div class="row">
                            <div class="col-lg-3 col-12">
                                <div class="mb-3">
                                    <label for="file" class="form-label">Preisliste Upload</label>
                                    <input type="file" class="form-control @error('file') is-invalid @enderror"
                                        value="{{ old('name') }}" name="file[]" id="file">
                                    @error('file')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-1 col-12 d-flex align-items-center">
                                <a href="javascript:;" class="btn-remove-row" class="text-danger"><i
                                        class="fa fa-times color-primary"></i></a>
                            </div>
                        </div>`;
            $(".more-fields").append(html);
        })
        $(document).on('click', '.btn-remove-row', function() {
            $(this).closest('.row').remove();
        });
    </script>
@endsection
