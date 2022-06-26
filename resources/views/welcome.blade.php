@extends('layouts.blank')
@section('main_container')
    <div class="page-content page-container" id="page-content">
        <div class="padding">
            <div class="row container d-flex justify-content-center">
                <div class="col-md-12">
                    <div class="card px-3">
                        <div class="card-body">
                            <h4 class="card-title">Awesome Todo list</h4>
                            <div class="add-items d-flex"> <input type="text" class="form-control todo-list-input"
                                    placeholder="What do you need to do today?"> <button
                                    class="add btn btn-primary font-weight-bold todo-list-add-btn">Add</button> </div>
                            <div class="list-wrapper">
                                <ul class="d-flex flex-column-reverse todo-list">
                                    {{-- <li class="completed">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="checkbox" type="checkbox" checked>
                                                test
                                                <i class="input-helper"></i></label>
                                        </div>
                                        <i class="remove mdi mdi-close-circle-outline"></i>
                                    </li> --}}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        (function($) {
            'use strict';
            $(function() {
                var todoListItem = $('.todo-list');
                var todoListInput = $('.todo-list-input');
                var items = [];


                // get all tasks
                $(document).ready(function() {
                    getAllTaks();
                });

                function refreshList() {
                    $.ajax({
                        type: "GET",
                        url: "/all-tasks",
                        dataType: "json",
                        success: function(response) {
                            if (response.length > 0) {
                                items = response;
                            }
                        }
                    });
                }

                function getAllTaks() {
                    $.ajax({
                        type: "GET",
                        url: "/all-tasks",
                        dataType: "json",
                        success: function(response) {
                            console.log(response);
                            if (response.length > 0) {
                                $.map(response, function(item, key) {
                                    var completed = item.checked ? 'completed' : '';
                                    var checked = item.checked ? 'checked' : '';
                                    todoListItem.append(
                                        "<li class='" + completed +
                                        "'><div class='form-check'><label class='form-check-label'><input class='checkbox' type='checkbox'" +
                                        checked + "/>" +
                                        item.text +
                                        "<i class='input-helper'></i></label></div><i class='remove mdi mdi-close-circle-outline'></i></li>"
                                    );

                                    todoListInput.val("");
                                });
                                items = response;
                            }

                        }
                    });
                }

                $('.todo-list-add-btn').on("click", function(event) {
                    event.preventDefault();
                    var item = $(this).prevAll('.todo-list-input').val();
                    $.ajax({
                        type: "POST",
                        url: "/new-task",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            text: item
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response) {
                                todoListItem.append(
                                    "<li><div class='form-check'><label class='form-check-label'><input class='checkbox' type='checkbox'/>" +
                                    response.text +
                                    "<i class='input-helper'></i></label></div><i class='remove mdi mdi-close-circle-outline'></i></li>"
                                );
                                todoListInput.val("");
                                refreshList();
                            }
                        }
                    });
                });
                todoListItem.on('change', '.checkbox', function() {
                    if ($(this).attr('checked')) {
                        $(this).removeAttr('checked');
                    } else {
                        $(this).attr('checked', 'checked');
                    }
                    var itemIndex = $(this).closest("li").index();
                    var itemObj = items[itemIndex];
                    var checked = false;
                    if ($(this).attr('checked') === 'checked') {
                        checked = true;
                    }
                    $.ajax({
                        type: "PUT",
                        url: "/update-task/",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            id: itemObj.id,
                            text: itemObj.text,
                            checked: checked,
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response) {
                                console.log(response);
                                $(this).closest("li").toggleClass('completed');
                            }
                        }
                    });
                });

                todoListItem.on('click', '.remove', function() {
                    var itemIndex = $(this).closest("li").index();
                    var itemObj = items[itemIndex];
                    var parent = $(this).parent();
                    $.ajax({
                        type: "DELETE",
                        url: "/delete-task/",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            id: itemObj.id,
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response) {
                                parent.remove();
                            }
                        }
                        
                    
                    });
                });

            });
        })(jQuery);
    </script>
@endsection
