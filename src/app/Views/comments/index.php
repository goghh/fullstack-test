<?= $this->extend('layouts/main') ?>
<?= $this->section('styles') ?>
<link rel="stylesheet" href="/css/comments.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div id="loading-overlay">
    <div class="spinner-border text-primary" role="status">
        <span class="sr-only">Загрузка...</span>
    </div>
</div>

<div class="container py-4">
    <h1 class="mb-4">Комментарии</h1>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-12 col-md-auto mb-2 mb-md-0">
                    <span class="font-weight-bold mr-2">Сортировка:</span>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary sort-btn active"
                                data-sort="id">
                            По ID <span class="sort-direction-icon" id="icon-id">↓</span>
                        </button>
                        <button type="button" class="btn btn-outline-secondary sort-btn"
                                data-sort="date">
                            По дате <span class="sort-direction-icon" id="icon-date"></span>
                        </button>
                    </div>
                </div>
                <div class="col-12 col-md-auto">
                    <span class="font-weight-bold mr-2">Направление:</span>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary dir-btn active"
                                data-dir="desc">По убыванию</button>
                        <button type="button" class="btn btn-outline-secondary dir-btn"
                                data-dir="asc">По возрастанию</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="comments-container"></div>

    <nav aria-label="Навигация по страницам" id="pagination-wrapper" class="mb-4" style="display:none">
        <ul class="pagination justify-content-center flex-wrap" id="pagination"></ul>
    </nav>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Добавить комментарий</h5>
        </div>
        <div class="card-body">
            <div id="form-alert" class="alert" role="alert" style="display:none"></div>
            <form id="comment-form" novalidate>
                <div class="form-group">
                    <label for="input-name">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="input-name" name="name"
                           placeholder="example@mail.com" required>
                    <div class="invalid-feedback" id="error-name"></div>
                </div>
                <div class="form-group">
                    <label for="input-text">Текст комментария <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="input-text" name="text" rows="4"
                              placeholder="Введите текст комментария..." required></textarea>
                    <div class="invalid-feedback" id="error-text"></div>
                </div>
                <div class="form-group">
                    <label for="input-date">Дата <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="input-date" name="date" required>
                    <div class="invalid-feedback" id="error-date"></div>
                </div>
                <button type="submit" class="btn btn-primary" id="submit-btn">
                    <span class="spinner-border spinner-border-sm mr-1" id="submit-spinner"
                          role="status" style="display:none"></span>
                    Отправить
                </button>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Подтверждение удаления</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">Вы уверены, что хотите удалить этот комментарий?</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-btn">Удалить</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="/js/comments.js"></script>
<?= $this->endSection() ?>