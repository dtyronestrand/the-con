## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.
## 2024-05-15 - Task Keyboard Accessibility
**Learning:** Hiding checkboxes with `display: none` breaks focusability. `group-hover:opacity-100` isn’t enough if you cannot hover. Custom UI elements must have keyboard listeners mapped to roles.
**Action:** Replaced `display: none` with Tailwind’s `sr-only` to keep elements focusable. Added `peer-focus-visible` styling for the custom checkbox, mapped Enter to save edits in `TaskInput.vue`, and added Space/Enter support for custom buttons with `group-focus-within` on task checkboxes.
