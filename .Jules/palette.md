## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.

## 2025-08-05 - Native Checkbox Focus and Styling in Vue Components
**Learning:** Using `display: none` on native `<input type="checkbox">` elements breaks keyboard accessibility as they cannot receive focus, preventing `:focus-visible` styling on sibling elements from functioning correctly.
**Action:** When styling custom form inputs, visually hide the native input using `opacity: 0; position: absolute; width: 1px; height: 1px;` instead of `display: none`. This allows the element to maintain focusability and trigger state changes correctly for keyboard and screen reader users.
