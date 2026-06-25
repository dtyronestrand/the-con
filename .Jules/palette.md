## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.

## 2025-06-25 - Custom Checkbox Keyboard Navigation
**Learning:** Using `display: none` on native `<input type="checkbox">` elements when creating custom styled checkboxes completely removes them from the document flow and tab order, destroying keyboard accessibility. Furthermore, custom clickable elements like `<p role="button">` require an explicit `tabindex` and keyboard event handlers (`@keydown.enter`, `@keydown.space`) to function properly for non-mouse users.
**Action:** When styling custom form inputs, hide the native input visually (e.g., `opacity: 0; position: absolute; width: 1px; height: 1px;`) rather than using `display: none`, and ensure sibling elements leverage `:focus-visible` to display focus rings. Always add `tabindex` and keydown event handlers to custom button roles.
