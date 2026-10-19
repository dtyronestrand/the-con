## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.
## 2024-05-24 - Custom Checkbox Keyboard Focus
**Learning:** Using `display: none` on native inputs for custom checkboxes completely removes them from the accessibility tree, making them un-focusable via keyboard.
**Action:** When creating custom checkboxes with Tailwind, use `sr-only` and `peer` on the native input. Style a sibling element using `peer-focus-visible` to render a visible focus ring without `display: none`.
