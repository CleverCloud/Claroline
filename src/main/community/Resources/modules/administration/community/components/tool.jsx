import React from 'react'
import {PropTypes as T} from 'prop-types'

import {Routes} from '#/main/app/router'

// app sections
import {ParametersTab} from '#/main/community/administration/community/parameters/components/parameters-tab'
import {UserTab} from '#/main/community/administration/community/user/components/user-tab'
import {GroupTab} from '#/main/community/administration/community/group/components/group-tab'
import {RoleTab} from '#/main/community/administration/community/role/components/role-tab'
import {OrganizationTab} from '#/main/community/administration/community/organization/components/organization-tab'
import {ProfileTab} from '#/main/community/administration/community/profile/components/profile-tab'

const CommunityTool = (props) =>
  <Routes
    path={props.path}
    redirect={[
      {from: '/', exact: true, to: '/users'}
    ]}
    routes={[
      {
        path: '/users',
        component: UserTab
      }, {
        path: '/groups',
        component: GroupTab
      }, {
        path: '/organizations',
        component: OrganizationTab
      }, {
        path: '/roles',
        component: RoleTab,
        displayed: props.isAdmin
      }, {
        path: '/profile',
        component: ProfileTab,
        displayed: props.isAdmin
      }, {
        path: '/parameters',
        component: ParametersTab,
        disabled: !props.isAdmin
      }
    ]}
  />

CommunityTool.propTypes = {
  path: T.string.isRequired,
  isAdmin: T.bool.isRequired
}

export {
  CommunityTool
}
