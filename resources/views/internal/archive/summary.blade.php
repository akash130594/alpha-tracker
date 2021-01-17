@extends('internal.layouts.new-app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>
                        <i class="fas fa-tachometer-alt"></i> @lang('Archive Report')
                    </strong>
                </div><!--card-header-->

                <div class="card-body">
                    <table class="table table-hover table-sm">
                        <thead>
                        <tr>
                            <th class="collapsible-th" colspan="3"></th>
                            <th class="collapsible-th">Starts</th>
                            <th class="collapsible-th">Completes</th>
                            <th class="collapsible-th">Terminates</th>
                            <th class="collapsible-th">Quotafull</th>
                            <th class="collapsible-th">Quality Terminate</th>
                            <th class="collapsible-th">Abandons</th>
                            <th class="collapsible-th">Abandon %</th>
                            <th class="collapsible-th">IR%</th>
                            <th class="collapsible-th">CPI</th>
                        </tr>
                        </thead>
                        <tbody class="">
                        <tr>
                            <th class="collapsible-th" colspan="3">
                                <label for="accounting">Summary</label>
                            </th>
                            <th class="collapsible-th">@if($archive->starts){{$archive->starts}}@else 0 @endif</th>
                            <th class="collapsible-th">@if($archive->completes){{$archive->completes}} @else 0 @endif</th>
                            <th class="collapsible-th">@if($archive->terminates){{$archive->terminates}} @else 0 @endif</th>
                            <th class="collapsible-th">@if($archive->quotafull){{$archive->quotafull}} @else 0 @endif</th>
                            <th class="collapsible-th">@if($archive->quality_terminate){{$archive->quality_terminate}} @else 0 @endif</th>
                            <th class="collapsible-th">{{$archive->abandons}}</th>
                            <th class="collapsible-th">
                                @php
                                    try{
                                        $ab = (($archive->abandons/$archive->starts) * 100);
                                    }catch(Exception $exception){
                                      $ab = 0;
                                    }
                                @endphp
                                {{round($ab)}}%
                            </th>
                            <th class="collapsible-th">
                                @php
                                    try{
                                        $ir = (($archive->completes/$archive->starts) * 100);
                                    }catch(Exception $exception){
                                      $ir = 0;
                                    }
                                @endphp
                                {{round($ir)}}%
                            </th>
                            <th class="collapsible-th">@if($archive->cpi){{$archive->cpi}} @else 0 @endif</th>
                        </tr>
                        </tbody>
                        <tbody class="collapsible-labels">
                        <tr class="clickable" data-toggle="collapse" data-target="#group-of-rows-1" aria-expanded="false" aria-controls="group-of-rows">
                            <td colspan="3">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                                Quota 1
                            </td>
                            <td class="collapsible-td">654</td>
                            <td class="collapsible-td">0</td>
                            <td class="collapsible-td">0</td>
                            <td class="collapsible-td">0</td>
                            <td class="collapsible-td">0</td>
                            <td class="collapsible-td">0</td>
                            <td class="collapsible-td">0</td>
                            <td class="collapsible-td">0</td>
                            <td class="collapsible-td">0</td>
                        </tr>
                        </tbody>
                        <tbody id="group-of-rows-1" class="collapse">
                            <tr>
                                <td colspan="3"></td>
                                <td class="collapsible-td">0</td>
                                <td class="collapsible-td">0</td>
                                <td class="collapsible-td">0</td>
                                <td class="collapsible-td">0</td>
                                <td class="collapsible-td">0</td>
                                <td class="collapsible-td">0</td>
                                <td class="collapsible-td">0</td>
                                <td class="collapsible-td">0</td>
                                <td class="collapsible-td">0</td>
                            </tr>
                        </tbody>
                        <tbody id="group-of-rows-1" class="collapse">
                        <tr>
                            <td colspan="3"></td>
                            <td class="collapsible-td">0</td>
                            <td class="collapsible-td">0</td>
                            <td class="collapsible-td">0</td>
                            <td class="collapsible-td">0</td>
                            <td class="collapsible-td">0</td>
                            <td class="collapsible-td">0</td>
                            <td class="collapsible-td">0</td>
                            <td class="collapsible-td">0</td>
                            <td class="collapsible-td">0</td>
                        </tr>
                        </tbody>
                        <tbody class="collapsible-labels">
                        <tr class="clickable" data-toggle="collapse" data-target="#group-of-rows-2" aria-expanded="false" aria-controls="group-of-rows">
                            <td colspan="3">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                                Quota 2
                            </td>
                            <td class="collapsible-td">654</td>
                            <td class="collapsible-td">0</td>
                            <td class="collapsible-td">0</td>
                            <td class="collapsible-td">0</td>
                            <td class="collapsible-td">0</td>
                            <td class="collapsible-td">0</td>
                            <td class="collapsible-td">0</td>
                            <td class="collapsible-td">0</td>
                            <td class="collapsible-td">0</td>
                        </tr>
                        </tbody>

                        <tbody class="collapsible-labels">
                        <tr class="clickable" data-toggle="collapse" data-target="#group-of-rows" aria-expanded="false" aria-controls="group-of-rows">
                            <td colspan="3">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                                Quota 3
                            </td>
                            <td class="collapsible-td">654</td>
                            <td class="collapsible-td">0</td>
                            <td class="collapsible-td">0</td>
                            <td class="collapsible-td">0</td>
                            <td class="collapsible-td">0</td>
                            <td class="collapsible-td">0</td>
                            <td class="collapsible-td">0</td>
                            <td class="collapsible-td">0</td>
                            <td class="collapsible-td">0</td>
                        </tr>
                        </tbody>
                    </table>
                </div> <!-- card-body -->
            </div><!-- card -->
        </div><!-- row -->
    </div><!-- row -->
    <!-- Modal -->
    <div id="client_link_test" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Link Checker</h4>
                </div>
                <div class="modal-body">
                    <iframe class="client_link_test_iframe" src=""></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('after-styles')
    <style>
        .collapsible-table.table {
            border-collapse: collapse;
            margin:50px auto;
        }
        th.collapsible-th {
            background: #3498db;
            color: white;
            font-weight: bold;
        }
        td.collapsible-td, th.collapsible-th {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
            font-size: 18px;
        }
        .collapsible-labels tr td {
            background-color: #2cc16a;
            font-weight: bold;
            color: #fff;
        }
        .collapsible-labels tr td label {
            display: block;
        }
    </style>
@endpush

@push('after-scripts')
    <script>

    </script>
@endpush
