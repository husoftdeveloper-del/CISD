# TODO

## 1) Plan confirmation
- [x] Identified where course selection/display is implemented: `dashboard.php`, `admission.php`, `edit_admission.php`, `edit_student.php`, `students_list.php`.
- [x] Confirmed course labels to store exactly in DB:
  - WEB DEVELOPMENT
  - APP DEVELOPMENT
  - AI & PYTHON
  - GRAPHIC DESIGNING
  - YOUTUBE AUTOMATION
  - DIGITAL MARKETING
  - BASIC COMPUTER SKILLS

## 2) Implement code changes (frontend + backend)
- [x] Update `admission.php` course `<select>` to include the 7 new courses.

- [x] Update `edit_admission.php` course field from text input to `<select>` with the full course list.

- [x] Update `edit_student.php` course field from text input to `<select>` with the full course list.

- [x] Update `dashboard.php` to add new course cards (with “View Details” links).

- [x] Create course detail pages (frontend) for the 7 new courses.

- [x] Update `students_list.php` course counts + tooltip filters to include the 7 new courses.

- [x] Update `dashboard.php` if needed (cards are already added).



## 3) Testing
- [ ] Open `admission.php` and verify new course options submit to DB.
- [x] Open `dashboard.php` and verify new cards render.

- [ ] Open `students_list.php` and verify counts/tooltips work for new courses.

- [ ] Open `edit_admission.php`/`edit_student.php` and verify course dropdown shows correct values.


