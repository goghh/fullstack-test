$(function () {
    'use strict';

    const state = {
        page:     1,
        sortBy:   'id',
        sortDir:  'desc',
        deleteId: null,
    };

    function loading(show) {
        $('#loading-overlay').toggleClass('show', show);
    }

    function escapeHtml(str) {
        return $('<div>').text(str).html();
    }

    function showFormAlert(message, type) {
        $('#form-alert')
            .removeClass('alert-success alert-danger')
            .addClass('alert-' + type)
            .text(message)
            .show();
    }

    function clearFormAlert() {
        $('#form-alert').hide().text('');
    }

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.trim());
    }

    function setFieldError($field, errorId, message) {
        $field.addClass('is-invalid').removeClass('is-valid');
        $('#' + errorId).text(message);
    }

    function setFieldValid($field) {
        $field.addClass('is-valid').removeClass('is-invalid');
        $('#error-' + $field.attr('id').replace('input-', '')).text('');
    }

    function clearFormValidation() {
        $('#comment-form .form-control').removeClass('is-invalid is-valid');
        $('.invalid-feedback').text('');
    }

    function validateForm() {
        let valid = true;
        const name = $('#input-name').val().trim();
        const text = $('#input-text').val().trim();
        const date = $('#input-date').val().trim();

        clearFormValidation();
        clearFormAlert();

        if (!name) {
            setFieldError($('#input-name'), 'error-name', 'Email обязателен для заполнения.');
            valid = false;
        } else if (!isValidEmail(name)) {
            setFieldError($('#input-name'), 'error-name', 'Введите корректный email.');
            valid = false;
        } else {
            setFieldValid($('#input-name'));
        }

        if (!text) {
            setFieldError($('#input-text'), 'error-text', 'Текст комментария обязателен.');
            valid = false;
        } else {
            setFieldValid($('#input-text'));
        }

        if (!date) {
            setFieldError($('#input-date'), 'error-date', 'Дата обязательна для заполнения.');
            valid = false;
        } else {
            setFieldValid($('#input-date'));
        }

        return valid;
    }

    function renderComments(comments) {
        const $container = $('#comments-container').empty();

        if (!comments || comments.length === 0) {
            $container.append(
                '<p class="text-muted text-center py-4">Комментариев пока нет. Будьте первым!</p>'
            );
            return;
        }

        $.each(comments, function (i, c) {
            $container.append(`
                <div class="card comment-card mb-3 shadow-sm" id="comment-${c.id}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap">
                            <div>
                                <h6 class="card-subtitle mb-1 text-primary">
                                    <a href="mailto:${escapeHtml(c.name)}">${escapeHtml(c.name)}</a>
                                </h6>
                                <small class="text-muted">
                                    ID: ${escapeHtml(String(c.id))} &nbsp;|&nbsp; Дата: ${escapeHtml(c.date)}
                                </small>
                            </div>
                            <button class="btn btn-sm btn-outline-danger delete-btn mt-2 mt-sm-0"
                                    data-id="${c.id}">
                                &times; Удалить
                            </button>
                        </div>
                        <p class="card-text mt-2 mb-0">${escapeHtml(c.text)}</p>
                    </div>
                </div>
            `);
        });
    }

    function renderPagination(totalPages, currentPage) {
        const $pag  = $('#pagination').empty();
        const $wrap = $('#pagination-wrapper');

        if (totalPages <= 1) {
            $wrap.hide();
            return;
        }

        $wrap.show();

        const prevDisabled = currentPage === 1 ? 'disabled' : '';
        const nextDisabled = currentPage === totalPages ? 'disabled' : '';

        $pag.append(`
            <li class="page-item ${prevDisabled}">
                <a class="page-link" href="#" data-page="${currentPage - 1}">&laquo;</a>
            </li>
        `);

        for (let p = 1; p <= totalPages; p++) {
            $pag.append(`
                <li class="page-item ${p === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${p}">${p}</a>
                </li>
            `);
        }

        $pag.append(`
            <li class="page-item ${nextDisabled}">
                <a class="page-link" href="#" data-page="${currentPage + 1}">&raquo;</a>
            </li>
        `);
    }

    function updateSortIcons() {
        const arrow = state.sortDir === 'asc' ? '↑' : '↓';
        $('#icon-id').text(state.sortBy === 'id'     ? arrow : '');
        $('#icon-date').text(state.sortBy === 'date' ? arrow : '');
    }

    function loadComments() {
        loading(true);

        $.get('/comments/list', {
            page:     state.page,
            sort_by:  state.sortBy,
            sort_dir: state.sortDir,
        })
        .done(function (res) {
            renderComments(res.comments);
            renderPagination(res.totalPages, res.page);
        })
        .fail(function () {
            showFormAlert('Ошибка при загрузке комментариев.', 'danger');
        })
        .always(function () {
            loading(false);
        });
    }

    function submitComment() {
        const $btn     = $('#submit-btn').prop('disabled', true);
        const $spinner = $('#submit-spinner').show();

        $.ajax({
            url:     '/comments',
            method:  'POST',
            data: {
                name: $('#input-name').val().trim(),
                text: $('#input-text').val().trim(),
                date: $('#input-date').val().trim(),
            },
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        })
        .done(function () {
            $('#comment-form')[0].reset();
            clearFormValidation();
            showFormAlert('Комментарий успешно добавлен!', 'success');
            state.page = 1;
            loadComments();
        })
        .fail(function (xhr) {
            const res = xhr.responseJSON;
            if (xhr.status === 422 && res?.errors) {
                clearFormValidation();
                if (res.errors.name) setFieldError($('#input-name'), 'error-name', res.errors.name);
                if (res.errors.text) setFieldError($('#input-text'), 'error-text', res.errors.text);
                if (res.errors.date) setFieldError($('#input-date'), 'error-date', res.errors.date);
            } else {
                showFormAlert('Ошибка при добавлении комментария.', 'danger');
            }
        })
        .always(function () {
            $btn.prop('disabled', false);
            $spinner.hide();
        });
    }

    function deleteComment(id) {
        const $btn = $('#confirm-delete-btn').prop('disabled', true);

        $.ajax({
            url:     '/comments/' + id,
            method:  'DELETE',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        })
        .done(function () {
            $('#deleteModal').modal('hide');
            loadComments();
        })
        .fail(function () {
            showFormAlert('Ошибка при удалении комментария.', 'danger');
        })
        .always(function () {
            $btn.prop('disabled', false);
            state.deleteId = null;
        });
    }

    $('#input-name').on('input blur', function () {
        const val = $(this).val().trim();
        if (!val) {
            setFieldError($(this), 'error-name', 'Email обязателен для заполнения.');
        } else if (!isValidEmail(val)) {
            setFieldError($(this), 'error-name', 'Введите корректный email.');
        } else {
            setFieldValid($(this));
        }
    });

    $('#comment-form').on('submit', function (e) {
        e.preventDefault();
        if (validateForm()) submitComment();
    });

    $('#comments-container').on('click', '.delete-btn', function () {
        state.deleteId = $(this).data('id');
        $('#deleteModal').modal('show');
    });

    $('#confirm-delete-btn').on('click', function () {
        if (state.deleteId) deleteComment(state.deleteId);
    });

    $('#pagination').on('click', 'a.page-link', function (e) {
        e.preventDefault();
        const $li = $(this).parent();
        if ($li.hasClass('disabled') || $li.hasClass('active')) return;
        state.page = parseInt($(this).data('page'), 10);
        loadComments();
        $('html, body').animate({ scrollTop: 0 }, 300);
    });

    $('.sort-btn').on('click', function () {
        const newSort = $(this).data('sort');
        state.sortDir = (state.sortBy === newSort && state.sortDir === 'desc') ? 'asc' : 'desc';
        state.sortBy  = newSort;

        $('.sort-btn').removeClass('active');
        $(this).addClass('active');

        $('.dir-btn').removeClass('active');
        $(`.dir-btn[data-dir="${state.sortDir}"]`).addClass('active');

        updateSortIcons();
        state.page = 1;
        loadComments();
    });

    $('.dir-btn').on('click', function () {
        const newDir = $(this).data('dir');
        if (state.sortDir === newDir) return;
        state.sortDir = newDir;

        $('.dir-btn').removeClass('active');
        $(this).addClass('active');

        updateSortIcons();
        state.page = 1;
        loadComments();
    });

    loadComments();
});