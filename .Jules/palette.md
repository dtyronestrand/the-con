## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.

## 2024-05-20 - Custom Checkbox Keyboard Focus
**Learning:** Custom checkboxes implementing visual styles by hiding the native input with `display: none` completely break keyboard accessibility and focus navigation.
**Action:** Use Tailwind's `sr-only` to hide the native input visually while retaining its accessibility, and pair it with `peer` and `peer-focus-visible` classes on the visual sibling to display focus indicators appropriately.
