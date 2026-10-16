'use strict';

const API = 'api/';
const $ = (id) => document.getElementById(id);

let students = [];        // the rows currently shown
let controller = null;    // lets us cancel an old search request
let listCalls = 0;

// ---------- One place that talks to the API ----------
class ApiError extends Error {
    constructor(message, status, errors) {
        super(message);
        this.status = status;
        this.errors = errors || {};
    }
}

async function apiRequest(endpoint, { method = 'GET', body, signal } = {}) {
    const options = { method, signal, headers: {} };
    if (body !== undefined) {
        options.headers['Content-Type'] = 'application/json';
        options.body = JSON.stringify(body);
    }

    let res;
    try {
        res = await fetch(API + endpoint, options);
    } catch (err) {
        if (err.name === 'AbortError') throw err;
        throw new ApiError('Network error. Is Apache running?', 0);
    }

    let data;
    try {
        data = await res.json();
    } catch (e) {
        throw new ApiError('The server did not return valid JSON.', res.status);
    }

    if (!res.ok || data.success === false) {
        throw new ApiError(data.message || 'Request failed.', res.status, data.errors);
    }
    return data;
}

// ---------- UI helpers ----------
let toastTimer;
function toast(message, type) {
    const t = $('toast');
    t.textContent = message;
    t.className = 'toast show ' + type;
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => { t.className = 'toast'; }, 3500);
}

function showRowMessage(text, cssClass) {
    const tr = document.createElement('tr');
    const td = document.createElement('td');
    td.colSpan = 6;
    td.className = 'state ' + cssClass;
    td.textContent = text;
    tr.appendChild(td);
    $('rows').replaceChildren(tr);
}

function clearErrors() {
    document.querySelectorAll('.err').forEach((el) => { el.textContent = ''; });
}

function showFieldErrors(errors) {
    Object.keys(errors || {}).forEach((key) => {
        const el = $('err-' + key);
        if (el) el.textContent = errors[key];
    });
}

function makeButton(label, action, id, cls) {
    const b = document.createElement('button');
    b.type = 'button';
    b.textContent = label;
    b.className = cls;
    b.dataset.action = action;
    b.dataset.id = id;
    return b;
}

function renderRows() {
    if (students.length === 0) {
        showRowMessage('No students found.', 'empty');
        return;
    }
    const frag = document.createDocumentFragment();
    students.forEach((s) => {
        const tr = document.createElement('tr');
        [s.id, s.name, s.email, s.phone, s.course].forEach((value) => {
            const td = document.createElement('td');
            td.textContent = value;           // textContent (not innerHTML) stops XSS
            tr.appendChild(td);
        });
        const actions = document.createElement('td');
        actions.className = 'actions';
        actions.append(
            makeButton('Edit', 'edit', s.id, 'btn small'),
            makeButton('Delete', 'delete', s.id, 'btn small danger')
        );
        tr.appendChild(actions);
        frag.appendChild(tr);
    });
    $('rows').replaceChildren(frag);
}

// ---------- LIST (GET) ----------
async function loadStudents() {
    if (controller) controller.abort();        // cancel the previous request if it is still running
    controller = new AbortController();

    const params = new URLSearchParams();
    const q = $('search').value.trim();
    const course = $('courseFilter').value;
    if (q) params.set('q', q);
    if (course) params.set('course', course);

    $('callCount').textContent = ++listCalls;
    showRowMessage('Loading students...', 'loading');     // loading state

    try {
        const res = await apiRequest('list.php?' + params.toString(), { signal: controller.signal });
        students = res.data;
        renderRows();
        $('summary').textContent = res.count + ' student(s) found';
    } catch (err) {
        if (err.name === 'AbortError') return;             // replaced by a newer request
        showRowMessage('Could not load students.', 'error');   // error state
        $('summary').textContent = '';
        toast(err.message, 'error');
    }
}

// ---------- Debounce (Intermediate extension) ----------
function debounce(fn, delay) {
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => fn(...args), delay);
    };
}
$('search').addEventListener('input', debounce(loadStudents, 400));
$('courseFilter').addEventListener('change', loadStudents);

// ---------- ADD / UPDATE (POST) ----------
function resetForm() {
    $('studentForm').reset();
    $('studentId').value = '';
    $('formTitle').textContent = 'Add Student';
    $('saveBtn').textContent = 'Add Student';
    $('cancelBtn').hidden = true;
    clearErrors();
}

function setSaving(saving) {
    const btn = $('saveBtn');
    btn.disabled = saving;
    if (saving) btn.textContent = 'Saving...';
    else btn.textContent = $('studentId').value ? 'Update Student' : 'Add Student';
}

$('studentForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    clearErrors();

    const id = $('studentId').value;
    const payload = {
        name:   $('name').value,
        email:  $('email').value,
        phone:  $('phone').value,
        course: $('course').value
    };
    if (id) payload.id = Number(id);

    setSaving(true);
    try {
        const res = await apiRequest(id ? 'update.php' : 'add.php', { method: 'POST', body: payload });
        toast(res.message, 'success');
        resetForm();
        await loadStudents();                    // refresh the table, no page reload
    } catch (err) {
        showFieldErrors(err.errors);
        toast(err.message, 'error');
    } finally {
        setSaving(false);
    }
});

$('cancelBtn').addEventListener('click', resetForm);

// ---------- EDIT / DELETE buttons (event delegation) ----------
$('rows').addEventListener('click', (e) => {
    const btn = e.target.closest('button[data-action]');
    if (!btn) return;
    const id = btn.dataset.id;
    if (btn.dataset.action === 'edit') startEdit(id);
    if (btn.dataset.action === 'delete') deleteStudent(id);
});

function startEdit(id) {
    const s = students.find((x) => String(x.id) === String(id));
    if (!s) return;
    clearErrors();
    $('studentId').value = s.id;
    $('name').value   = s.name;
    $('email').value  = s.email;
    $('phone').value  = s.phone;
    $('course').value = s.course;
    $('formTitle').textContent = 'Edit Student #' + s.id;
    $('saveBtn').textContent = 'Update Student';
    $('cancelBtn').hidden = false;
    $('name').focus();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

async function deleteStudent(id) {
    const s = students.find((x) => String(x.id) === String(id));
    if (!confirm('Delete ' + (s ? s.name : 'this student') + '? This cannot be undone.')) return;

    try {
        const res = await apiRequest('delete.php', { method: 'POST', body: { id: Number(id) } });
        toast(res.message, 'success');
        if ($('studentId').value === String(id)) resetForm();
        await loadStudents();
    } catch (err) {
        toast(err.message, 'error');
        loadStudents();                          // refresh in case the row was already gone
    }
}

// ---------- Start ----------
loadStudents();