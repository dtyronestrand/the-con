## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.
## 2024-04-24 - Accessibility for Task Buttons
**Learning:** Decorative text inside interactive elements (like the `+` or `-` buttons in the Task subtasks list) is incorrectly read aloud by screen readers if not hidden.
**Action:** Always wrap non-semantic visual text characters used as button icons in `<span aria-hidden="true">` when an `aria-label` is present to ensure screen reader clarity.
