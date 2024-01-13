import { useState } from "react";
import { useNavigate } from "react-router-dom";

export const useCampaignEntityFormUi = (redirectPath: string) => {
  const navigate = useNavigate()
  const redirect = () => navigate(redirectPath)
  const [ tab, setTab ] = useState<'details' | 'permissions'>('details')


  return { redirect, setTab, tab }
}