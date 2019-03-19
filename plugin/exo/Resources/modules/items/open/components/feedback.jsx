import React from 'react'
import {PropTypes as T} from 'prop-types'

import {tex} from '#/main/app/intl/translation'
import {HtmlText} from '#/main/core/layout/components/html-text'

const OpenFeedback = props =>
  <div className="open-feedback">
    {props.answer && 0 !== props.answer.length ?
      <HtmlText>{props.answer}</HtmlText>
      :
      <div className="no-answer">{tex('no_answer')}</div>
    }
  </div>

OpenFeedback.propTypes = {
  answer: T.string
}

OpenFeedback.defaultProps = {
  answer: ''
}

export {
  OpenFeedback
}