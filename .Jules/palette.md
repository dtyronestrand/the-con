## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.

## 2024-05-30 - Custom Checkbox Input Accessibility
**Learning:** Using `display: none` on native inputs for custom styled forms breaks keyboard accessibility by removing the element from the focus order.
**Action:** When creating custom styled inputs, visually hide the native input using `opacity: 0; position: absolute; width: 1px; height: 1px;` and apply focus styles to the adjacent custom element using `:focus-visible`.
