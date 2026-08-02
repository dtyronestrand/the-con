## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.

## 2024-05-15 - Implicit Blur Save Accessibility
**Learning:** Components that rely on implicit `@blur` events to save data (like task inputs) often lack an intuitive keyboard submission mechanism, leaving keyboard users frustrated.
**Action:** When a component saves on `@blur`, always bind `@keydown.enter` to the same blur/save handler to support natural keyboard interactions. And always replace `display: none` with visually hidden classes on native inputs (like checkboxes) to preserve tab order.
