import { FC } from "react";
import Modal from "../../../../../../../components/layout/Modal";
import { useUserFormModalState } from "./UserFormModal.state";
import { UserFormModalProps } from "./UserFormModal.types";
import UserFormModalView from "./UserFormModal.view";

const UserFormModal: FC<UserFormModalProps> = ({ user, onFinish }) => {
  const { errors, input, roles, save, update } = useUserFormModalState(user, onFinish)

  return <Modal id="user-modal" trigger={<></>}>
    <UserFormModalView state={{ errors, input, roles, save, update }} />
  </Modal>
}

export default UserFormModal