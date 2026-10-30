(function () {
  'use strict';

  const $ = (sel, root) => (root || document).querySelector(sel);
  const $$ = (sel, root) => Array.from((root || document).querySelectorAll(sel));

  const THEME_KEY = 'theme';
  const FONT_KEY = 'font';

  function storedTheme() {
    try {
      return localStorage.getItem(THEME_KEY) || 'system';
    } catch (e) {
      return 'system';
    }
  }

  function mediaDark() {
    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
  }

  function applyTheme(theme) {
    const dark = theme === 'dark' || (theme === 'system' && mediaDark());
    document.documentElement.classList.toggle('dark', dark);
    const meta = document.querySelector('meta[name="theme-color"]');
    if (meta) meta.setAttribute('content', dark ? '#020817' : '#ffffff');

    $$('[data-theme-check]').forEach((el) => {
      el.classList.toggle('hidden', el.dataset.themeCheck !== theme);
    });
    $$('[data-theme-option]').forEach((el) => {
      el.checked = el.value === theme;
    });
  }

  function setTheme(theme) {
    try {
      localStorage.setItem(THEME_KEY, theme);
    } catch (e) {
      /* ignore */
    }
    applyTheme(theme);
  }

  function applyFont(font) {
    const html = document.documentElement;
    html.classList.remove('font-manrope', 'font-system');
    if (font === 'manrope' || font === 'system') html.classList.add('font-' + font);
  }

  function setFont(font) {
    try {
      localStorage.setItem(FONT_KEY, font);
    } catch (e) {
      /* ignore */
    }
    applyFont(font);
  }

  applyTheme(storedTheme());
  applyFont((function () {
    try {
      return localStorage.getItem(FONT_KEY) || 'inter';
    } catch (e) {
      return 'inter';
    }
  })());

  if (window.matchMedia) {
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
      if (storedTheme() === 'system') applyTheme('system');
    });
  }

  /* ------------------------------------------------------------------ */
  /* Toasts                                                              */
  /* ------------------------------------------------------------------ */

  function showToast(message, variant) {
    const container = $('#toast-container');
    if (!container) return;

    const el = document.createElement('div');
    el.className = 'toast';
    el.setAttribute('role', 'status');
    el.innerHTML =
      '<svg class="icon"><use href="#icon-' +
      (variant === 'error' ? 'circle-alert' : 'circle-check') +
      '"></use></svg>' +
      '<div class="flex-1 text-sm"></div>' +
      '<button type="button" class="rounded-sm p-0.5 opacity-60 transition-opacity hover:opacity-100" aria-label="Close notification">' +
      '<svg class="icon icon-sm"><use href="#icon-x"></use></svg></button>';

    el.querySelector('.flex-1').textContent = message;
    el.querySelector('button').addEventListener('click', () => el.remove());
    container.appendChild(el);
    window.setTimeout(() => {
      el.style.opacity = '0';
      el.style.transform = 'translateY(6px)';
      el.style.transition = 'opacity .2s ease, transform .2s ease';
      window.setTimeout(() => el.remove(), 220);
    }, 3600);
  }

  function showFlash() {
    const flash = $('#flash-message');
    if (!flash) return;
    const message = flash.dataset.message;
    if (message) showToast(message);
    flash.remove();
  }

  /* ------------------------------------------------------------------ */
  /* Sidebar                                                             */
  /* ------------------------------------------------------------------ */

  function toggleSidebar(force) {
    const open = typeof force === 'boolean' ? force : !document.body.classList.contains('sidebar-open');
    document.body.classList.toggle('sidebar-open', open);
  }

  document.addEventListener('click', (e) => {
    if (e.target.closest('[data-sidebar-toggle]')) {
      toggleSidebar();
      return;
    }
    if (e.target.closest('[data-sidebar-close]')) {
      toggleSidebar(false);
    }
  });

  $$('[data-collapse-trigger]').forEach((trigger) => {
    trigger.addEventListener('click', () => {
      const item = trigger.closest('.collapsible');
      if (!item) return;
      const open = item.classList.toggle('open');
      trigger.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  });

  /* ------------------------------------------------------------------ */
  /* Dropdowns                                                           */
  /* ------------------------------------------------------------------ */

  function closeAllMenus(except) {
    $$('[data-dropdown]').forEach((menu) => {
      if (menu === except) return;
      menu.classList.add('hidden');
      const wrapper = menu.closest('.dropdown');
      if (wrapper) {
        $$('[data-dropdown-trigger]', wrapper).forEach((t) => t.setAttribute('aria-expanded', 'false'));
      }
    });
  }

  document.addEventListener('click', (e) => {
    const trigger = e.target.closest('[data-dropdown-trigger]');
    if (trigger) {
      e.preventDefault();
      const wrapper = trigger.closest('.dropdown');
      if (!wrapper) return;
      const menu = $('[data-dropdown]', wrapper);
      if (!menu) return;
      const willOpen = menu.classList.contains('hidden');
      closeAllMenus(menu);
      menu.classList.toggle('hidden', !willOpen);
      trigger.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
      return;
    }

    if (!e.target.closest('[data-dropdown]')) {
      closeAllMenus();
    }
  });

  document.addEventListener('click', (e) => {
    if (e.target.closest('[data-set-theme]')) {
      setTheme(e.target.closest('[data-set-theme]').dataset.setTheme);
      closeAllMenus();
    }
    if (e.target.closest('[data-dialog-open]')) {
      closeAllMenus();
    }
  });

  /* ------------------------------------------------------------------ */
  /* Dialogs                                                             */
  /* ------------------------------------------------------------------ */

  function openDialog(id) {
    const el = typeof id === 'string' ? document.getElementById(id) : id;
    if (!el) return;
    el.classList.add('is-open');
    document.body.style.overflow = 'hidden';
    const focusable = el.querySelector('[autofocus], input:not([type=hidden]), select, textarea, button');
    window.setTimeout(() => {
      if (focusable) focusable.focus({ preventScroll: true });
    }, 60);
    document.dispatchEvent(new CustomEvent('dialog:open', { detail: { id: el.id, dialog: el } }));
  }

  function closeDialog(id) {
    const el = typeof id === 'string' ? document.getElementById(id) : id;
    if (!el) return;
    el.classList.remove('is-open');
    if (!$('.dialog.is-open')) document.body.style.overflow = '';
    document.dispatchEvent(new CustomEvent('dialog:close', { detail: { id: el.id, dialog: el } }));
  }

  document.addEventListener('click', (e) => {
    const opener = e.target.closest('[data-dialog-open]');
    if (opener) {
      e.preventDefault();
      openDialog(opener.dataset.dialogOpen);
      return;
    }

    const closer = e.target.closest('[data-dialog-close]');
    if (closer) {
      e.preventDefault();
      closeDialog(closer.closest('.dialog'));
      return;
    }

    if (e.target.classList.contains('dialog-overlay')) {
      closeDialog(e.target.closest('.dialog'));
    }
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeAllMenus();
      const openDialogs = $$('.dialog.is-open');
      if (openDialogs.length) closeDialog(openDialogs[openDialogs.length - 1]);
      toggleSidebar(false);
    }
  });

  /* ------------------------------------------------------------------ */
  /* Command menu                                                        */
  /* ------------------------------------------------------------------ */

  const commandInput = $('[data-command-input]');
  const commandItems = $$('[data-command-item]');
  let commandIndex = 0;

  function filterCommands() {
    if (!commandInput) return;
    const q = commandInput.value.trim().toLowerCase();
    const visible = [];
    commandItems.forEach((item) => {
      const match = !q || item.textContent.toLowerCase().includes(q) || (item.dataset.keywords || '').includes(q);
      item.classList.toggle('hidden', !match);
      if (match) visible.push(item);
    });
    commandIndex = 0;
    commandItems.forEach((item) => item.classList.remove('active'));
    if (visible[0]) visible[0].classList.add('active');
  }

  function moveCommand(step) {
    const visible = commandItems.filter((item) => !item.classList.contains('hidden'));
    if (!visible.length) return;
    commandItems.forEach((item) => item.classList.remove('active'));
    commandIndex = (commandIndex + step + visible.length) % visible.length;
    visible[commandIndex].classList.add('active');
    visible[commandIndex].scrollIntoView({ block: 'nearest' });
  }

  if (commandInput) {
    commandInput.addEventListener('input', filterCommands);
    commandInput.addEventListener('keydown', (e) => {
      if (e.key === 'ArrowDown') {
        e.preventDefault();
        moveCommand(1);
      } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        moveCommand(-1);
      } else if (e.key === 'Enter') {
        e.preventDefault();
        const active = $('[data-command-item].active');
        if (active && active.dataset.href) window.location.href = active.dataset.href;
      }
    });
  }

  document.addEventListener('keydown', (e) => {
    if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
      const dialog = $('#command-dialog');
      if (!dialog) return;
      e.preventDefault();
      if (dialog.classList.contains('is-open')) {
        closeDialog(dialog);
      } else {
        openDialog(dialog);
      }
    }
  });

  document.addEventListener('dialog:open', (e) => {
    if (e.detail.id !== 'command-dialog') return;
    if (commandInput) {
      commandInput.value = '';
      filterCommands();
      window.setTimeout(() => commandInput.focus(), 30);
    }
  });

  document.addEventListener('click', (e) => {
    const item = e.target.closest('[data-command-item]');
    if (item) {
      if (item.dataset.href) window.location.href = item.dataset.href;
      else closeDialog('command-dialog');
    }
  });

  /* ------------------------------------------------------------------ */
  /* Password visibility                                                 */
  /* ------------------------------------------------------------------ */

  document.addEventListener('click', (e) => {
    const toggle = e.target.closest('[data-password-toggle]');
    if (!toggle) return;
    const input = document.getElementById(toggle.dataset.passwordToggle);
    if (!input) return;
    const show = input.type === 'password';
    input.type = show ? 'text' : 'password';
    const use = toggle.querySelector('use');
    if (use) use.setAttribute('href', show ? '#icon-eye-off' : '#icon-eye');
    toggle.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
  });

  /* ------------------------------------------------------------------ */
  /* OTP inputs                                                          */
  /* ------------------------------------------------------------------ */

  const otpInputs = $$('[data-otp-input]');
  const otpSubmit = $('[data-otp-submit]');

  function updateOtp() {
    if (!otpSubmit) return;
    otpSubmit.disabled = otpInputs.some((input) => !input.value);
  }

  otpInputs.forEach((input, index) => {
    input.addEventListener('input', () => {
      input.value = input.value.replace(/\D/g, '').slice(0, 1);
      if (input.value && otpInputs[index + 1]) otpInputs[index + 1].focus();
      updateOtp();
    });
    input.addEventListener('keydown', (e) => {
      if (e.key === 'Backspace' && !input.value && otpInputs[index - 1]) {
        otpInputs[index - 1].focus();
      }
    });
    input.addEventListener('paste', (e) => {
      const text = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
      if (!text) return;
      e.preventDefault();
      otpInputs.forEach((slot, i) => {
        slot.value = text[i] || '';
      });
      const next = otpInputs.findIndex((slot) => !slot.value);
      const focusIndex = next === -1 ? otpInputs.length - 1 : next;
      if (otpInputs[focusIndex]) otpInputs[focusIndex].focus();
      updateOtp();
    });
  });
  updateOtp();

  /* ------------------------------------------------------------------ */
  /* Demo forms                                                          */
  /* ------------------------------------------------------------------ */

  document.addEventListener('submit', (e) => {
    const form = e.target.closest('[data-demo-form]');
    if (!form) return;
    e.preventDefault();

    if (form.id === 'appearance-form') {
      const font = form.querySelector('[name="font"]');
      const theme = form.querySelector('[name="theme"]:checked');
      if (font) setFont(font.value);
      if (theme) setTheme(theme.value);
    }

    const button = form.querySelector('button[type="submit"]');
    const original = button ? button.innerHTML : '';
    const message = form.dataset.message || 'Changes saved successfully.';

    if (form.dataset.redirect) {
      if (button) {
        button.disabled = true;
        button.innerHTML =
          '<svg class="icon animate-spin"><use href="#icon-loader-circle"></use></svg>' +
          '<span>' + (form.dataset.loading || 'Please wait...') + '</span>';
      }
      showToast(message);
      window.setTimeout(() => {
        window.location.href = form.dataset.redirect;
      }, 900);
      return;
    }

    showToast(message);
    if (form.dataset.closeDialog) closeDialog(form.dataset.closeDialog);
    if (form.hasAttribute('data-reset')) form.reset();
    if (button && original) {
      button.disabled = true;
      window.setTimeout(() => {
        button.disabled = false;
        button.innerHTML = original;
      }, 700);
    }

    document.dispatchEvent(new CustomEvent('demo-form:submit', { detail: { form } }));
  });

  /* ------------------------------------------------------------------ */
  /* Add / remove URL rows                                               */
  /* ------------------------------------------------------------------ */

  document.addEventListener('click', (e) => {
    const add = e.target.closest('[data-add-url]');
    if (add) {
      const list = document.getElementById(add.dataset.addUrl);
      const row = list && list.querySelector('.url-row');
      if (!list || !row) return;
      const clone = row.cloneNode(true);
      const input = clone.querySelector('input');
      if (input) input.value = '';
      list.appendChild(clone);
    }

    const remove = e.target.closest('[data-remove-url]');
    if (remove) {
      const row = remove.closest('.url-row');
      const list = row && row.parentElement;
      if (row && list && list.querySelectorAll('.url-row').length > 1) row.remove();
    }
  });

  /* ------------------------------------------------------------------ */
  /* Generic data table                                                  */
  /* ------------------------------------------------------------------ */

  class DataTable {
    constructor(container) {
      this.container = container;
      this.table = $('table', container);
      this.tbody = $('tbody', container);
      this.rows = $$('tr[data-row]', this.tbody);
      this.searchInput = $('[data-table-search]', container);
      this.pageSizeSelect = $('[data-page-size]', container);
      this.emptyRow = $('[data-empty-row]', container);
      this.state = {
        search: '',
        page: 1,
        pageSize: Number(this.pageSizeSelect ? this.pageSizeSelect.value : container.dataset.pageSize) || 10,
        filters: {},
        sortField: null,
        sortDir: 'asc',
      };

      this.filterGroups = $$('[data-filter-group]', container).map((el) => ({
        key: el.dataset.filter,
        inputs: $$('input[type="checkbox"]', el),
      }));
      this.filterGroups.forEach((group) => {
        this.state.filters[group.key] = [];
      });

      this.bind();
      this.render();
      container.__dataTable = this;
      window.__dataTables = window.__dataTables || [];
      window.__dataTables.push(this);
    }

    bind() {
      if (this.searchInput) {
        this.searchInput.addEventListener('input', () => {
          this.state.search = this.searchInput.value.trim().toLowerCase();
          this.state.page = 1;
          this.render();
        });
      }

      this.filterGroups.forEach((group) => {
        group.inputs.forEach((input) => {
          input.addEventListener('change', () => {
            this.state.filters[group.key] = group.inputs.filter((i) => i.checked).map((i) => i.value);
            this.state.page = 1;
            this.render();
          });
        });
      });

      $$('[data-table-reset]', this.container).forEach((btn) => {
        btn.addEventListener('click', () => {
          if (this.searchInput) this.searchInput.value = '';
          this.state.search = '';
          this.state.filters = {};
          this.filterGroups.forEach((group) => {
            group.inputs.forEach((input) => {
              input.checked = false;
            });
            this.state.filters[group.key] = [];
          });
          this.state.page = 1;
          this.state.sortField = null;
          this.render();
        });
      });

      $$('[data-page-action]', this.container).forEach((btn) => {
        btn.addEventListener('click', () => {
          const action = btn.dataset.pageAction;
          const pages = Math.max(1, Math.ceil(this.filtered().length / this.state.pageSize));
          if (action === 'first') this.state.page = 1;
          if (action === 'prev') this.state.page = Math.max(1, this.state.page - 1);
          if (action === 'next') this.state.page = Math.min(pages, this.state.page + 1);
          if (action === 'last') this.state.page = pages;
          this.render();
        });
      });

      if (this.pageSizeSelect) {
        this.pageSizeSelect.addEventListener('change', () => {
          this.state.pageSize = Number(this.pageSizeSelect.value) || 10;
          this.state.page = 1;
          this.render();
        });
      }

      $$('[data-sort]', this.container).forEach((btn) => {
        btn.addEventListener('click', () => {
          const field = btn.dataset.sort;
          if (this.state.sortField === field) {
            this.state.sortDir = this.state.sortDir === 'asc' ? 'desc' : 'asc';
          } else {
            this.state.sortField = field;
            this.state.sortDir = 'asc';
          }
          this.render();
        });
      });

      $$('[data-column-toggle]', this.container).forEach((input) => {
        input.addEventListener('change', () => this.renderColumns());
      });

      this.container.addEventListener('change', (e) => {
        const checkbox = e.target.closest('.row-select');
        if (checkbox) {
          const row = checkbox.closest('tr');
          if (row) row.classList.toggle('selected', checkbox.checked);
          this.updateSelection();
        }

        const selectAll = e.target.closest('[data-select-all]');
        if (selectAll) {
          this.visibleRows().forEach((row) => {
            const box = $('.row-select', row);
            if (box) {
              box.checked = selectAll.checked;
              row.classList.toggle('selected', selectAll.checked);
            }
          });
          this.updateSelection();
        }
      });

      $$('[data-bulk-delete]', this.container).forEach((btn) => {
        btn.addEventListener('click', () => {
          window.__pendingBulkTable = this;
        });
      });

      this.renderColumns();
    }

    allRows() {
      return this.rows.filter((row) => row.isConnected);
    }

    filtered() {
      const rows = this.allRows();
      return rows.filter((row) => {
        if (this.state.search && !(row.dataset.search || '').includes(this.state.search)) return false;
        return Object.keys(this.state.filters).every((key) => {
          const selected = this.state.filters[key];
          if (!selected.length) return true;
          return selected.includes(row.dataset[key] || '');
        });
      });
    }

    sorted(rows) {
      if (!this.state.sortField) return rows;
      const field = this.state.sortField;
      const dir = this.state.sortDir === 'asc' ? 1 : -1;
      return rows.slice().sort((a, b) => {
        const av = a.getAttribute('data-sort-' + field) || '';
        const bv = b.getAttribute('data-sort-' + field) || '';
        const an = Number(av);
        const bn = Number(bv);
        if (av !== '' && bv !== '' && !Number.isNaN(an) && !Number.isNaN(bn)) return (an - bn) * dir;
        return av.localeCompare(bv, undefined, { numeric: true, sensitivity: 'base' }) * dir;
      });
    }

    visibleRows() {
      const rows = this.allRows();
      return rows.filter((row) => !row.classList.contains('hidden'));
    }

    render() {
      const filtered = this.filtered();
      const sorted = this.sorted(filtered);
      const total = sorted.length;
      const pages = Math.max(1, Math.ceil(total / this.state.pageSize));
      if (this.state.page > pages) this.state.page = pages;
      const start = (this.state.page - 1) * this.state.pageSize;
      const end = Math.min(start + this.state.pageSize, total);

      this.allRows().forEach((row) => row.classList.add('hidden'));
      sorted.slice(start, end).forEach((row) => {
        row.classList.remove('hidden');
        this.tbody.appendChild(row);
      });

      if (this.emptyRow) {
        this.emptyRow.classList.toggle('hidden', total !== 0);
        this.tbody.appendChild(this.emptyRow);
      }

      const info = $('[data-table-info]', this.container);
      if (info) info.textContent = total === 0 ? 'No results.' : 'Showing ' + (start + 1) + '-' + end + ' of ' + total + ' row(s)';

      const pageLabel = $('[data-page-label]', this.container);
      if (pageLabel) pageLabel.textContent = 'Page ' + this.state.page + ' of ' + pages;

      $$('[data-page-action]', this.container).forEach((btn) => {
        const action = btn.dataset.pageAction;
        btn.disabled = action === 'first' || action === 'prev' ? this.state.page <= 1 : this.state.page >= pages;
      });

      $$('[data-sort]', this.container).forEach((btn) => {
        const active = btn.dataset.sort === this.state.sortField;
        btn.classList.toggle('text-foreground', active);
        btn.setAttribute('aria-sort', active ? (this.state.sortDir === 'asc' ? 'ascending' : 'descending') : 'none');
      });

      this.updateSelection();
      this.renderColumns();
    }

    updateSelection() {
      const boxes = $$('.row-select', this.tbody);
      const selected = boxes.filter((box) => box.checked);
      const selectAll = $('[data-select-all]', this.container);
      const visible = this.visibleRows().map((row) => $('.row-select', row)).filter(Boolean);
      const visibleSelected = visible.filter((box) => box.checked);

      if (selectAll) {
        selectAll.checked = visible.length > 0 && visibleSelected.length === visible.length;
        selectAll.indeterminate = visibleSelected.length > 0 && visibleSelected.length < visible.length;
      }

      const bulk = $('[data-bulk-actions]', this.container);
      const count = selected.length;
      if (bulk) {
        bulk.classList.toggle('hidden', count === 0);
        const label = $('[data-selected-count]', bulk);
        const total = $('[data-selected-total]', bulk);
        if (label) label.textContent = String(count);
        if (total) total.textContent = String(this.filtered().length);
      }
    }

    renderColumns() {
      $$('[data-column-toggle]', this.container).forEach((input) => {
        const col = input.dataset.columnToggle;
        $$('[data-col="' + col + '"]', this.table).forEach((cell) => {
          cell.classList.toggle('hidden', !input.checked);
        });
      });
    }

    removeRows(rows) {
      rows.forEach((row) => row.remove());
      this.render();
    }

    removeSelected() {
      const rows = $$('.row-select', this.tbody)
        .filter((box) => box.checked)
        .map((box) => box.closest('tr'))
        .filter(Boolean);
      this.removeRows(rows);
    }
  }

  $$('.js-data-table').forEach((container) => new DataTable(container));

  document.addEventListener('click', (e) => {
    if (e.target.closest('[data-confirm-bulk-delete]')) {
      const table = window.__pendingBulkTable;
      if (table) {
        table.removeSelected();
        showToast('Selected rows deleted.');
        window.__pendingBulkTable = null;
      }
      closeDialog(e.target.closest('.dialog'));
    }
  });

  /* ------------------------------------------------------------------ */
  /* Row actions                                                         */
  /* ------------------------------------------------------------------ */

  document.addEventListener('click', (e) => {
    const edit = e.target.closest('[data-row-edit]');
    if (edit) {
      const row = edit.closest('tr');
      const dialog = document.getElementById(edit.dataset.rowEdit);
      closeAllMenus();
      if (!row || !dialog) return;

      $$('[data-edit-field]', dialog).forEach((input) => {
        const field = input.getAttribute('data-edit-field');
        const value = row.getAttribute('data-edit-' + field) || '';
        input.value = value;
      });
      openDialog(dialog);
      return;
    }

    const remove = e.target.closest('[data-delete-row]');
    if (remove) {
      const row = remove.closest('tr');
      const container = remove.closest('.js-data-table');
      closeAllMenus();
      if (!row) return;
      row.remove();
      if (container && container.__dataTable) container.__dataTable.render();
      showToast('Row deleted.');
    }
  });

  /* ------------------------------------------------------------------ */
  /* Apps page                                                           */
  /* ------------------------------------------------------------------ */

  const appsGrid = $('[data-apps-grid]');

  if (appsGrid) {
    const search = $('[data-apps-search]');
    const type = $('[data-apps-type]');
    const sort = $('[data-apps-sort]');
    const empty = $('[data-apps-empty]');

    const apply = () => {
      const q = (search ? search.value : '').trim().toLowerCase();
      const t = type ? type.value : 'all';
      const direction = sort ? sort.value : 'asc';
      const cards = $$('[data-app]', appsGrid);

      cards.sort((a, b) => {
        const an = (a.dataset.name || '').toLowerCase();
        const bn = (b.dataset.name || '').toLowerCase();
        return direction === 'asc' ? an.localeCompare(bn) : bn.localeCompare(an);
      });
      cards.forEach((card) => appsGrid.appendChild(card));

      let visible = 0;
      cards.forEach((card) => {
        const connected = card.dataset.connected === 'true';
        const match =
          (!q || (card.dataset.name || '').includes(q) || (card.dataset.desc || '').includes(q)) &&
          (t === 'all' || (t === 'connected' ? connected : !connected));
        card.classList.toggle('hidden', !match);
        if (match) visible += 1;
      });

      if (empty) empty.classList.toggle('hidden', visible !== 0);
    };

    if (search) search.addEventListener('input', apply);
    if (type) type.addEventListener('change', apply);
    if (sort) sort.addEventListener('change', apply);

    appsGrid.addEventListener('click', (e) => {
      const btn = e.target.closest('[data-connect-app]');
      if (!btn) return;
      const card = btn.closest('[data-app]');
      const connected = card.dataset.connected !== 'true';
      card.dataset.connected = connected ? 'true' : 'false';
      btn.dataset.connected = connected ? 'true' : 'false';
      btn.textContent = connected ? 'Connected' : 'Connect';
      showToast(connected ? card.dataset.name + ' connected.' : card.dataset.name + ' disconnected.');
    });

    apply();
  }

  /* ------------------------------------------------------------------ */
  /* Chats page                                                          */
  /* ------------------------------------------------------------------ */

  const chatList = $('[data-chat-list]');

  if (chatList) {
    const right = $('[data-chat-right]');
    const empty = $('[data-chat-empty]');

    const openChat = (target) => {
      $$('[data-chat-panel]').forEach((panel) => {
        panel.classList.toggle('hidden', panel.dataset.chatPanel !== target);
      });
      $$('[data-chat-target]', chatList).forEach((item) => {
        item.classList.toggle('active', item.dataset.chatTarget === target);
      });
      if (empty) empty.classList.add('hidden');
      if (right) right.classList.add('mobile-open');
    };

    chatList.addEventListener('click', (e) => {
      const item = e.target.closest('[data-chat-target]');
      if (!item) return;
      openChat(item.dataset.chatTarget);
      const panel = $('[data-chat-panel="' + item.dataset.chatTarget + '"]');
      if (panel) {
        const messages = $('[data-chat-messages]', panel);
        if (messages) messages.scrollTop = messages.scrollHeight;
      }
    });

    const back = $('[data-chat-back]');
    if (back) back.addEventListener('click', () => right && right.classList.remove('mobile-open'));

    const search = $('[data-chat-search]');
    if (search) {
      search.addEventListener('input', () => {
        const q = search.value.trim().toLowerCase();
        $$('[data-chat-target]', chatList).forEach((item) => {
          item.classList.toggle('hidden', !(item.dataset.name || '').includes(q));
        });
      });
    }

    document.addEventListener('submit', (e) => {
      const form = e.target.closest('[data-chat-form]');
      if (!form) return;
      e.preventDefault();
      const input = form.querySelector('input[name="message"]');
      if (!input || !input.value.trim()) return;
      const panel = $('[data-chat-panel]:not(.hidden)');
      const messages = panel ? $('[data-chat-messages]', panel) : null;
      if (!messages) return;

      const wrap = document.createElement('div');
      wrap.className = 'chat-bubble chat-bubble-out';
      wrap.textContent = input.value.trim();
      const time = document.createElement('span');
      time.className = 'chat-time';
      time.textContent = new Date().toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
      wrap.appendChild(time);
      messages.appendChild(wrap);
      messages.scrollTop = messages.scrollHeight;
      input.value = '';
    });

    $$('[data-new-chat-user]').forEach((btn) => {
      btn.addEventListener('click', () => {
        const target = btn.dataset.newChatUser;
        closeDialog('new-chat-dialog');
        const item = $('[data-chat-target="' + target + '"]');
        if (item) {
          openChat(target);
          item.scrollIntoView({ block: 'nearest' });
        } else {
          showToast('Conversation opened.');
        }
      });
    });

    const firstChat = $('[data-chat-target]', chatList);
    if (firstChat && window.matchMedia('(min-width: 640px)').matches) {
      firstChat.click();
    }
  }

  /* ------------------------------------------------------------------ */
  /* Appearance page                                                     */
  /* ------------------------------------------------------------------ */

  const fontSelect = $('[data-font-select]');
  if (fontSelect) {
    try {
      fontSelect.value = localStorage.getItem(FONT_KEY) || 'inter';
    } catch (e) {
      /* ignore */
    }
    fontSelect.addEventListener('change', () => {
      applyFont(fontSelect.value);
      const desc = $('[data-font-desc]');
      if (desc) desc.classList.toggle('font-manrope', fontSelect.value === 'manrope');
    });
  }

  $$('[data-theme-option]').forEach((input) => {
    input.addEventListener('change', () => {
      if (input.checked) setTheme(input.value);
    });
  });

  /* ------------------------------------------------------------------ */
  /* Disabled nav links                                                  */
  /* ------------------------------------------------------------------ */

  document.addEventListener('click', (e) => {
    const disabled = e.target.closest('a[aria-disabled="true"], button[aria-disabled="true"]');
    if (disabled) e.preventDefault();
  });

  /* ------------------------------------------------------------------ */
  /* Boot                                                                */
  /* ------------------------------------------------------------------ */

  showFlash();

  window.App = {
    openDialog: openDialog,
    closeDialog: closeDialog,
    showToast: showToast,
    setTheme: setTheme,
    setFont: setFont,
    closeMenus: closeAllMenus,
    getTable: (id) => {
      const el = document.getElementById(id);
      return el ? el.__dataTable : null;
    },
  };
})();
