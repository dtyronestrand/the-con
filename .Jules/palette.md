## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.

## 2024-05-15 - Custom Checkbox and Button Accessibility
**Learning:** Using `display: none` on native form inputs like `<input type="checkbox">` removes them from the accessibility tree and prevents keyboard focus. Additionally, `role="button"` elements must have `tabindex="0"` and handle keyboard events (Enter/Space) to be fully accessible.
**Action:** Visually hide native inputs (using `opacity: 0; position: absolute; width: 1px; height: 1px;`) when creating custom form controls to maintain keyboard focus ability, and style sibling elements using `:focus-visible`. Always add `tabindex="0"` and `@keydown.enter` / `@keydown.space.prevent` when converting generic elements to buttons.
