import { ReactNode } from "react";

export type BaseRoute = {
  path: string
  element: JSX.Element | ReactNode
  index?: boolean
  children?: Route[]
}

export type IndexRoute = {
  element: JSX.Element | ReactNode
  index: true
}

export type Route = BaseRoute | IndexRoute