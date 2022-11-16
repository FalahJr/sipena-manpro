<!-- partial:partials/_settings-panel.html -->
  <div id="settings-trigger"><i class="mdi mdi-settings"></i></div>
  <div id="right-sidebar" class="settings-panel">
    <i class="settings-close mdi mdi-close"></i>
    <ul class="nav nav-tabs bg-gradient-primary" id="setting-panel" role="tablist">
      <!-- <li class="nav-item">
        <a class="nav-link active" id="todo-tab" data-toggle="tab" href="#todo-section" role="tab" aria-controls="todo-section" aria-expanded="true">TO DO LIST</a>
      </li> -->
      <li class="nav-item">
        <a class="nav-link active" id="logs-tab" data-toggle="tab" href="#user-log-activity" role="tab" aria-expanded="true" aria-controls="user-log-activity">User Log Activity</a>
      </li>
    </ul>
    <div class="tab-content" id="setting-content">
      <!-- User log activity -->
      {{-- <div class="tab-pane fade show active scroll-wrapper" id="user-log-activity" role="tabpanel" aria-labelledby="user-log-activity">
        <div class="d-flex align-items-center justify-content-between border-bottom">
          <p class="settings-heading border-top-0 mb-3 pl-3 pt-0 border-bottom-0 pb-0">Log Activity</p>
        </div>
        <ul class="chat-list" id="showlog">

        </ul>
        <br>
        <div class="col-12">
          <button type="button" class="btn btn-primary" onclick="clearlog()" name="button" style="width:100%; margin-top:-40px; background-color:red; border:none; color:white;">Clear All</button>
          <button type="button" class="btn btn-primary" onclick="window.location.href='{{route('log.index')}}'" name="button" style="width:100%; margin-bottom:70px;border:none;">See Details</button>
        </div>
      </div> --}}

      <!-- To do section tab ends -->
      <div class="tab-pane fade" id="todo-section" role="tabpanel" aria-labelledby="todo-section">
        <div class="add-items d-flex px-3 mb-0">
          <form class="form w-100">
            <div class="form-group d-flex">
              <input type="text" class="form-control todo-list-input" placeholder="Add To-do">
              <button type="submit" class="add btn btn-primary todo-list-add-btn" id="add-task">Add</button>
            </div>
          </form>
        </div>
        <div class="list-wrapper px-3">
          <ul class="d-flex flex-column-reverse todo-list">
            <li>
              <div class="form-check">
                <label class="form-check-label">
                  <input class="checkbox" type="checkbox">
                  Team review meeting at 3.00 PM
                </label>
              </div>
              <i class="remove mdi mdi-close-circle-outline"></i>
            </li>
            <li>
              <div class="form-check">
                <label class="form-check-label">
                  <input class="checkbox" type="checkbox">
                  Prepare for presentation
                </label>
              </div>
              <i class="remove mdi mdi-close-circle-outline"></i>
            </li>
            <li>
              <div class="form-check">
                <label class="form-check-label">
                  <input class="checkbox" type="checkbox">
                  Resolve all the low priority tickets due today
                </label>
              </div>
              <i class="remove mdi mdi-close-circle-outline"></i>
            </li>
            <li class="completed">
              <div class="form-check">
                <label class="form-check-label">
                  <input class="checkbox" type="checkbox" checked>
                  Schedule meeting for next week
                </label>
              </div>
              <i class="remove mdi mdi-close-circle-outline"></i>
            </li>
            <li class="completed">
              <div class="form-check">
                <label class="form-check-label">
                  <input class="checkbox" type="checkbox" checked>
                  Project review
                </label>
              </div>
              <i class="remove mdi mdi-close-circle-outline"></i>
            </li>
          </ul>
        </div>
        <div class="events py-4 border-bottom px-3">
          <div class="wrapper d-flex mb-2">
            <i class="mdi mdi-circle-outline text-primary mr-2"></i>
            <span>Feb 11 2018</span>
          </div>
          <p class="mb-0 font-weight-thin text-gray">Creating component page</p>
          <p class="text-gray mb-0">build a js based app</p>
        </div>
        <div class="events pt-4 px-3">
          <div class="wrapper d-flex mb-2">
            <i class="mdi mdi-circle-outline text-primary mr-2"></i>
            <span>Feb 7 2018</span>
          </div>
          <p class="mb-0 font-weight-thin text-gray">Meeting with Alisa</p>
          <p class="text-gray mb-0 ">Call Sarah Graves</p>
        </div>
      </div>
      <!-- chat tab ends -->
    </div>
  </div>
  <div id="theme-settings" class="settings-panel">
    <i class="settings-close mdi mdi-close"></i>
    <p class="settings-heading">SIDEBAR SKINS</p>
    <div class="sidebar-bg-options selected" id="sidebar-default-theme"><div class="img-ss rounded-circle bg-gradient-dark border mr-3"></div>Default</div>
    <div class="sidebar-bg-options" id="sidebar-light-theme"><div class="img-ss rounded-circle bg-light border mr-3"></div>Light</div>
    <p class="settings-heading mt-2">HEADER SKINS</p>
    <div class="color-tiles mx-0 px-4">
      <div class="tiles primary"></div>
      <div class="tiles success"></div>
      <div class="tiles warning"></div>
      <div class="tiles danger"></div>
      <div class="tiles pink"></div>
      <div class="tiles info"></div>
      <div class="tiles dark"></div>
      <div class="tiles light"></div>
    </div>
  </div>
  <!-- partial -->
