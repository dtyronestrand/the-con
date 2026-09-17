## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.
## 2025-02-18 - Avoid 'display: none' on Custom Form Inputs
**Learning:** When styling custom checkboxes or radio buttons, using `display: none` on the native input entirely removes it from the accessibility tree, making it invisible to screen readers and un-focusable via keyboard navigation.
**Action:** Always visually hide native inputs using Tailwind's `sr-only` utility class and apply focus styling to sibling custom elements using the `peer` and `peer-focus-visible` pattern to ensure full accessibility while maintaining the custom design.
