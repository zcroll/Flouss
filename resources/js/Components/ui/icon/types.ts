import { type VariantProps, cva } from "class-variance-authority"

export const icons = {
  'building-office': 'BuildingOfficeIcon',
  'map': 'MapIcon',
  'beaker': 'BeakerIcon',
  'computer-desktop': 'ComputerDesktopIcon',
  'office-building': 'BuildingOffice2Icon',
  'chevron-up': 'ChevronUpIcon',
  'chevron-down': 'ChevronDownIcon',
  'chevron-left': 'ChevronLeftIcon',
  'chevron-right': 'ChevronRightIcon',
} as const

export type IconName = keyof typeof icons

export const iconVariants = cva("", {
  variants: {
    variant: {
      default: "text-foreground",
      muted: "text-muted-foreground",
      primary: "text-primary",
      destructive: "text-destructive",
    },
    size: {
      default: "h-5 w-5",
      sm: "h-4 w-4",
      lg: "h-6 w-6",
    },
  },
  defaultVariants: {
    variant: "default",
    size: "default",
  },
})

type IconVariantProps = VariantProps<typeof iconVariants>

export interface IconProps {
  name: IconName
  variant?: IconVariantProps['variant']
  size?: IconVariantProps['size']
  className?: string
} 