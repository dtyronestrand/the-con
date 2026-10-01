## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.
## 2024-05-20 - Custom Checkbox Input Accessibility
**Learning:** Using `display: none` on native `<input type="checkbox">` elements completely removes them from the accessibility tree, breaking keyboard navigation and screen reader support.
**Action:** Use Tailwind's `sr-only` class to hide the native input visually while keeping it accessible. Pair it with the `peer` class on the input and `peer-focus-visible` on a sibling stylistic element to easily add accessible focus rings.
