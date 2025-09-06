<!-- BEGIN: main -->

<div class="max-w-3xl mx-auto px-4 py-10">
  <!-- Header -->
  <div class="text-center mb-8">
    <h1 class="text-3xl font-extrabold text-gray-800 flex items-center justify-center gap-2">
      <span>🍴</span>
      Đặt Món cho Bàn <span class="text-blue-600">#{RESERVATION_ID}</span>
    </h1>
    <p class="text-gray-500 mt-2">Khám phá thực đơn và chọn món ăn yêu thích của bạn!</p>
  </div>

  <!-- Notifications -->
  <!-- BEGIN: error -->
  <div class="alert alert-danger text-center mb-3">{ERROR}</div>
  <!-- END: error -->

  <!-- BEGIN: success -->
  <div class="alert alert-success text-center mb-3" role="alert">
    {SUCCESS}
  </div>
  <!-- END: success -->

  <!-- BEGIN: success_action -->
  <div class="text-center mb-3">
    <a href="{ORDER_MORE_URL}" class="btn btn-primary px-4 py-2 rounded-lg shadow me-2">
      🔁 Đặt thêm món
    </a>
    <a href="{BACK_URL}" class="btn btn-secondary px-4 py-2 rounded-lg shadow">
      ⬅️ Quay lại
    </a>
  </div>
  <!-- END: success_action -->

  <!-- Form -->
  <form action="{FORM_ACTION}" method="post" class="space-y-6" {FORM_HIDE}>
    <div class="accordion shadow rounded-xl overflow-hidden" id="menuAccordion">
      <!-- BEGIN: category -->
      <div class="accordion-item">
        <h2 class="accordion-header" id="heading-{CATEGORY_ID}">
          <button class="list-button accordion-button collapsed flex items-center gap-2 font-semibold text-gray-800"
                  type="button"
                  data-bs-toggle="collapse"
                  data-bs-target="#collapse-{CATEGORY_ID}"
                  aria-expanded="false"
                  aria-controls="collapse-{CATEGORY_ID}">
            📋 {CATEGORY_NAME}
          </button>
        </h2>
        <div id="collapse-{CATEGORY_ID}"
             class="accordion-collapse collapse"
             aria-labelledby="heading-{CATEGORY_ID}"
             data-bs-parent="#menuAccordion">
          <div class="accordion-body bg-white">
            <div class="row g-3">
              <!-- BEGIN: item -->
              <div class="col-md-4">
                <div class="card h-100 shadow-sm rounded-xl border-0">
                  <div class="card-body">
                    <h5 class="card-title font-semibold text-gray-800">
                      {ITEM_NAME}<span class="text-sm text-red-500">{STATUS_TEXT}</span>
                    </h5>
                    <p class="card-text {STATUS_CLASS}">{ITEM_PRICE} đ</p>

                    <!-- BEGIN: item_if_con -->
                    <div class="mt-2">
                      <input type="number"
                             name="quantity[{ITEM_ID}]"
                             class="form-control"
                             min="0"
                             value="0">
                    </div>
                    <!-- END: item_if_con -->
                  </div>
                </div>
              </div>
              <!-- END: item -->
            </div>
          </div>
        </div>
      </div>
      <!-- END: category -->
    </div>

    <!-- Submit button -->
    <div class="text-center mt-6">
      <input type="hidden" name="submit" value="1">
      <button class="button-save" type="submit">
        ✅ Xác Nhận Đặt Món
      </button>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<style>
/* Accordion button */
.list-button {
  width: 100%;
  margin-top: 16px;
  padding: 14px 20px;
  border-radius: 12px;
  background: #ffffff;
  color: #333;
  font-size: 1.05rem;
  font-weight: 600;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  border: 1px solid #e5e7eb;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
  transition: all 0.3s ease;
}
.list-button:hover {
  background: #f9fafb;
  color: #2563eb;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}
.list-button:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.3);
}
.accordion-button:not(.collapsed) {
  background: #eef6ff !important;
  color: #2563eb !important;
  border-color: #bfdbfe;
}

/* Submit button */
.button-save {
  margin-top: 24px;
  padding: 14px 32px;
  border-radius: 50px;
  background: linear-gradient(135deg, #2563eb, #1d4ed8);
  color: #fff;
  font-size: 1.1rem;
  font-weight: 600;
  border: none;
  cursor: pointer;
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
  transition: all 0.3s ease;
}
.button-save:hover {
  background: linear-gradient(135deg, #1d4ed8, #1e40af);
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
}
.button-save:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

/* Alerts */
.alert-success {
  background-color: #d1e7dd !important;
  color: #0f5132 !important;
  border-color: #badbcc !important;
}

/* Fix Bootstrap button colors */
.btn-primary {
  background-color: #0d6efd !important;
  color: #fff !important;
  border-color: #0d6efd !important;
}
.btn-secondary {
  background-color: #6c757d !important;
  color: #fff !important;
  border-color: #6c757d !important;
}
</style>

<!-- END: main -->
