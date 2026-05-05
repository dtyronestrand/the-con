## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.

## 2026-05-05 - Keyboard focus visibility in visually hidden form inputs
**Learning:** When creating custom inputs like checkboxes that use `display: none` on the native input, it breaks keyboard accessibility because the browser won't focus an element with `display: none`. Also, icon-only buttons need ARIA labels, and text symbols acting as icons should have `aria-hidden='true'`.
**Action:** Use `opacity: 0; position: absolute; width: 1px; height: 1px;` to visually hide native inputs instead of `display: none` so they remain keyboard focusable, and use `:focus-visible` on sibling elements to render the focus state. Always wrap purely visual text symbols in `aria-hidden='true'`.
