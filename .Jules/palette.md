## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.

## $(date +%Y-%m-%d) - Keyboard Accessibility for Tasks
**Learning:** Using `display: none` on native checkboxes breaks screen reader and keyboard accessibility since they can't be focused or interacted with. Custom elements mimicking native interactions (like the task open button) must implement `tabindex="0"` and handle both `Enter` and `Space` keydowns to provide a fully accessible experience.
**Action:** When hiding native inputs for custom styling, always use Tailwind's `sr-only` utility instead of `display: none`, and utilize `peer` classes to style siblings for `focus-visible`. Always ensure interactive non-button elements support both `Enter` and `Space` key events.
