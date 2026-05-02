## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.

## 2024-05-14 - Custom Form Inputs Keyboard Accessibility
**Learning:** Using `display: none` on native `<input>` elements (e.g. for custom styled checkboxes) completely removes them from the accessibility tree, breaking keyboard navigation entirely as the element can no longer receive focus.
**Action:** When building custom styled inputs like checkboxes, hide the native input visually instead of using `display: none` (e.g. via `opacity: 0; position: absolute; width: 1px; height: 1px;`). Then style a visible sibling element using the `:focus-visible` pseudo-class on the hidden native input (e.g. `input:focus-visible ~ span { ... }`) to provide a focus ring for keyboard users.
